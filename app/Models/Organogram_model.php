<?php

namespace App\Models;

/**
 * Organogram Model
 * Manages organizational hierarchy, diagram node coordinates, connectors,
 * palette pool of unplaced offices, and bottom-to-top approval chain resolution.
 */
class Organogram_model extends Crud_model {

    protected $table = null;

    public function __construct() {
        $this->table = 'organogram_nodes';
        parent::__construct($this->table);
    }

    /**
     * Get all nodes currently on the canvas
     */
    public function get_placed_nodes() {
        $nodes_table = $this->db->prefixTable('organogram_nodes');
        $roles_table = $this->db->prefixTable('roles');
        $depts_table = $this->db->prefixTable('departments');
        $users_table = $this->db->prefixTable('users');

        $sql = "SELECT n.*,
                       r.title AS role_title,
                       r.rank AS role_rank,
                       d.title AS department_title,
                       d.code AS department_code,
                       p_role.title AS parent_role_title,
                       (SELECT string_agg(CONCAT(u.first_name, ' ', u.last_name), ', ')
                        FROM $users_table u
                        WHERE u.role_id = r.id AND u.deleted = 0 AND u.status = 'active') AS office_holders,
                       (SELECT COUNT(u.id)
                        FROM $users_table u
                        WHERE u.role_id = r.id AND u.deleted = 0 AND u.status = 'active') AS holders_count
                FROM $nodes_table n
                JOIN $roles_table r ON r.id = n.role_id AND r.deleted = 0
                JOIN $depts_table d ON d.id = n.department_id AND d.deleted = 0
                LEFT JOIN $nodes_table p_node ON p_node.id = n.parent_node_id
                LEFT JOIN $roles_table p_role ON p_role.id = p_node.role_id
                ORDER BY n.tier_level ASC, n.id ASC";

        return $this->db->query($sql)->getResult();
    }

    /**
     * Get all active connectors (arrows) between nodes
     */
    public function get_connectors() {
        $conn_table = $this->db->prefixTable('organogram_connectors');
        $nodes_table = $this->db->prefixTable('organogram_nodes');

        $sql = "SELECT c.*,
                       fn.role_id AS from_role_id,
                       tn.role_id AS to_role_id
                FROM $conn_table c
                JOIN $nodes_table fn ON fn.id = c.from_node_id
                JOIN $nodes_table tn ON tn.id = c.to_node_id
                ORDER BY c.id ASC";

        return $this->db->query($sql)->getResult();
    }

    /**
     * Get palette items (departments and roles not yet placed on canvas)
     * Once an office/role is placed on canvas, it is excluded to prevent duplication!
     */
    public function get_unplaced_palette_items() {
        $roles_table = $this->db->prefixTable('roles');
        $depts_table = $this->db->prefixTable('departments');
        $nodes_table = $this->db->prefixTable('organogram_nodes');
        $users_table = $this->db->prefixTable('users');

        // Roles not in organogram_nodes
        $sqlRoles = "SELECT r.*,
                            d.title AS department_title,
                            d.code AS department_code,
                            (SELECT COUNT(u.id) FROM $users_table u WHERE u.role_id = r.id AND u.deleted = 0 AND u.status = 'active') AS holders_count
                     FROM $roles_table r
                     JOIN $depts_table d ON d.id = r.department_id AND d.deleted = 0
                     WHERE r.deleted = 0
                       AND r.id NOT IN (SELECT role_id FROM $nodes_table)
                     ORDER BY d.title ASC, r.rank ASC, r.title ASC";

        $unplacedRoles = $this->db->query($sqlRoles)->getResult();

        // All active departments with counts of unplaced roles
        $sqlDepts = "SELECT d.*,
                            (SELECT COUNT(r.id)
                             FROM $roles_table r
                             WHERE r.department_id = d.id
                               AND r.deleted = 0
                               AND r.id NOT IN (SELECT role_id FROM $nodes_table)) AS unplaced_roles_count,
                            (SELECT COUNT(n.id)
                             FROM $nodes_table n
                             WHERE n.department_id = d.id) AS placed_roles_count
                     FROM $depts_table d
                     WHERE d.deleted = 0
                     ORDER BY d.title ASC";

        $depts = $this->db->query($sqlDepts)->getResult();

        // Group unplaced roles by department
        $grouped = [];
        foreach ($depts as $dept) {
            $grouped[$dept->id] = [
                'department' => $dept,
                'roles' => []
            ];
        }

        foreach ($unplacedRoles as $role) {
            if (isset($grouped[$role->department_id])) {
                $grouped[$role->department_id]['roles'][] = $role;
            }
        }

        return [
            'departments' => $depts,
            'grouped_roles' => array_values($grouped),
            'total_unplaced' => count($unplacedRoles)
        ];
    }

    /**
     * Add a role to the canvas as an organogram node
     */
    public function place_role_on_canvas($role_id, $pos_x = 150.0, $pos_y = 150.0, $parent_node_id = null) {
        $roles_table = $this->db->prefixTable('roles');
        $nodes_table = $this->db->prefixTable('organogram_nodes');

        // Check if already placed
        $exists = $this->db->table($nodes_table)->where('role_id', $role_id)->get()->getRow();
        if ($exists) {
            return $exists->id;
        }

        $role = $this->db->table($roles_table)->where('id', $role_id)->get()->getRow();
        if (!$role) {
            return false;
        }

        $rank = $role->rank ?: 3;
        $isApex = ($rank == 1) ? 1 : 0;

        $nodeData = [
            'role_id' => $role_id,
            'department_id' => $role->department_id,
            'parent_node_id' => $parent_node_id,
            'tier_level' => $rank,
            'pos_x' => (float)$pos_x,
            'pos_y' => (float)$pos_y,
            'is_apex' => $isApex,
            'approval_limit_ugx' => ($rank == 1) ? 500000000.00 : (($rank == 2) ? 50000000.00 : (($rank == 3) ? 10000000.00 : 2000000.00)),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table($nodes_table)->insert($nodeData);
        $newNodeId = $this->db->insertID();

        // If parent node specified, auto-create connector
        if ($parent_node_id) {
            $this->link_nodes($newNodeId, $parent_node_id);
        } else {
            // Auto-find parent if apex or superior exists
            $this->auto_assign_parent_and_connector($newNodeId, $role->department_id, $rank);
        }

        return $newNodeId;
    }

    /**
     * Automatically link a node to an appropriate superior in the hierarchy
     */
    public function auto_assign_parent_and_connector($nodeId, $deptId, $rank) {
        $nodes_table = $this->db->prefixTable('organogram_nodes');

        if ($rank == 1) {
            // Apex node has no parent
            return;
        }

        // 1. Look for a superior office in the same department (lower rank number = higher hierarchy)
        $superiorInDept = $this->db->table($nodes_table)
            ->where('department_id', $deptId)
            ->where('id !=', $nodeId)
            ->where('tier_level <', $rank)
            ->orderBy('tier_level', 'DESC')
            ->get()->getRow();

        if ($superiorInDept) {
            $this->set_parent_and_link($nodeId, $superiorInDept->id);
            return;
        }

        // 2. Otherwise, link to the Apex node (General Secretary, rank 1 or is_apex 1)
        $apexNode = $this->db->table($nodes_table)
            ->where('is_apex', 1)
            ->get()->getRow();

        if (!$apexNode) {
            $apexNode = $this->db->table($nodes_table)
                ->where('tier_level', 1)
                ->get()->getRow();
        }

        if ($apexNode && $apexNode->id != $nodeId) {
            $this->set_parent_and_link($nodeId, $apexNode->id);
        }
    }

    /**
     * Set parent node and create/update connector
     */
    public function set_parent_and_link($fromNodeId, $toNodeId) {
        $nodes_table = $this->db->prefixTable('organogram_nodes');

        // Prevent self-reference or cycles
        if ($fromNodeId == $toNodeId || $this->would_create_cycle($fromNodeId, $toNodeId)) {
            return false;
        }

        $this->db->table($nodes_table)->where('id', $fromNodeId)->update([
            'parent_node_id' => $toNodeId,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->link_nodes($fromNodeId, $toNodeId);
        return true;
    }

    /**
     * Create connector arrow from subordinate to superior
     */
    public function link_nodes($fromNodeId, $toNodeId, $workflowType = 'general') {
        $conn_table = $this->db->prefixTable('organogram_connectors');

        // Check if connector already exists
        $exists = $this->db->table($conn_table)
            ->where('from_node_id', $fromNodeId)
            ->where('to_node_id', $toNodeId)
            ->get()->getRow();

        if (!$exists) {
            $this->db->table($conn_table)->insert([
                'from_node_id' => $fromNodeId,
                'to_node_id' => $toNodeId,
                'workflow_type' => $workflowType,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Unlink connector between two nodes
     */
    public function unlink_nodes($fromNodeId, $toNodeId) {
        $conn_table = $this->db->prefixTable('organogram_connectors');
        $nodes_table = $this->db->prefixTable('organogram_nodes');

        $this->db->table($conn_table)
            ->where('from_node_id', $fromNodeId)
            ->where('to_node_id', $toNodeId)
            ->delete();

        // Also clear parent_node_id if matching
        $this->db->table($nodes_table)
            ->where('id', $fromNodeId)
            ->where('parent_node_id', $toNodeId)
            ->update(['parent_node_id' => null, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Remove node from canvas (returns it back to right palette!)
     */
    public function remove_node_from_canvas($nodeId) {
        $nodes_table = $this->db->prefixTable('organogram_nodes');
        $conn_table = $this->db->prefixTable('organogram_connectors');

        // Re-parent children if any
        $node = $this->db->table($nodes_table)->where('id', $nodeId)->get()->getRow();
        if ($node) {
            $grandParent = $node->parent_node_id;
            $this->db->table($nodes_table)
                ->where('parent_node_id', $nodeId)
                ->update(['parent_node_id' => $grandParent]);

            // Delete connectors involving this node
            $this->db->table($conn_table)
                ->where('from_node_id', $nodeId)
                ->orWhere('to_node_id', $nodeId)
                ->delete();

            // Delete node
            $this->db->table($nodes_table)->where('id', $nodeId)->delete();
            return true;
        }

        return false;
    }

    /**
     * Update node coordinates during drag on canvas
     */
    public function update_node_position($nodeId, $posX, $posY) {
        $nodes_table = $this->db->prefixTable('organogram_nodes');
        return $this->db->table($nodes_table)->where('id', $nodeId)->update([
            'pos_x' => (float)$posX,
            'pos_y' => (float)$posY,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Check if setting parent would introduce a circular loop
     */
    public function would_create_cycle($fromNodeId, $toNodeId) {
        $nodes_table = $this->db->prefixTable('organogram_nodes');
        $curr = $toNodeId;
        $visited = [];

        while ($curr) {
            if ($curr == $fromNodeId) {
                return true; // Cycle detected!
            }
            if (in_array($curr, $visited)) {
                return true;
            }
            $visited[] = $curr;
            $row = $this->db->table($nodes_table)->select('parent_node_id')->where('id', $curr)->get()->getRow();
            $curr = $row ? $row->parent_node_id : null;
        }

        return false;
    }

    /**
     * Resolve the Bottom-to-Top Approval Chain
     * Traverses upward from any initiating office to its supervising authorities up to the Apex
     */
    public function resolve_approval_chain($startRoleIdOrNodeId, $isRoleId = true, $amountUgx = 0) {
        $nodes_table = $this->db->prefixTable('organogram_nodes');
        $roles_table = $this->db->prefixTable('roles');
        $depts_table = $this->db->prefixTable('departments');
        $users_table = $this->db->prefixTable('users');

        if ($isRoleId) {
            $startNode = $this->db->table($nodes_table)->where('role_id', $startRoleIdOrNodeId)->get()->getRow();
        } else {
            $startNode = $this->db->table($nodes_table)->where('id', $startRoleIdOrNodeId)->get()->getRow();
        }

        if (!$startNode) {
            return [
                'success' => false,
                'message' => 'Initiating office is not registered on the organogram canvas.',
                'chain' => []
            ];
        }

        $chain = [];
        $visited = [];
        $curr = $startNode->id;
        $step = 1;

        while ($curr && !in_array($curr, $visited)) {
            $visited[] = $curr;

            $nodeDetails = $this->db->query("
                SELECT n.*,
                       r.title AS role_title,
                       r.rank AS role_rank,
                       d.title AS department_title,
                       d.code AS department_code,
                       (SELECT string_agg(CONCAT(u.first_name, ' ', u.last_name), ', ')
                        FROM $users_table u
                        WHERE u.role_id = r.id AND u.deleted = 0 AND u.status = 'active') AS office_holders
                FROM $nodes_table n
                JOIN $roles_table r ON r.id = n.role_id
                JOIN $depts_table d ON d.id = n.department_id
                WHERE n.id = $curr
            ")->getRow();

            if (!$nodeDetails) {
                break;
            }

            // Exclude the initiator from approving their own document; only parents approve
            if ($curr != $startNode->id) {
                $chain[] = [
                    'step' => $step++,
                    'node_id' => $nodeDetails->id,
                    'role_id' => $nodeDetails->role_id,
                    'office_title' => $nodeDetails->role_title,
                    'department' => $nodeDetails->department_title,
                    'tier_level' => $nodeDetails->tier_level,
                    'approval_limit_ugx' => (float)$nodeDetails->approval_limit_ugx,
                    'office_holders' => $nodeDetails->office_holders ?: 'Vacant / Unassigned',
                    'action_required' => ($nodeDetails->is_apex == 1) ? 'Final Accounting Officer Sanction' : 'Operational Verification & Recommendation',
                    'is_apex' => (int)$nodeDetails->is_apex
                ];
            }

            // Terminate if apex node reached or no parent
            if ($nodeDetails->is_apex == 1 || empty($nodeDetails->parent_node_id)) {
                break;
            }

            $curr = $nodeDetails->parent_node_id;
        }

        return [
            'success' => true,
            'initiator' => [
                'node_id' => $startNode->id,
                'role_id' => $startNode->role_id
            ],
            'chain' => $chain,
            'total_steps' => count($chain)
        ];
    }

    /**
     * Compute clean hierarchical auto-layout positions for all placed nodes
     * Aligns with the dual-branching paper infographic structure
     */
    public function compute_auto_layout() {
        $nodes = $this->get_placed_nodes();
        if (empty($nodes)) {
            return false;
        }

        // Ideal coordinates for dual-branching tree
        $dualCoordinates = [
            1 => [ // Level A (Apex, 1 node)
                ['pos_x' => 960.0, 'pos_y' => 60.0]
            ],
            2 => [ // Level B (Directors, 2 nodes)
                ['pos_x' => 520.0, 'pos_y' => 240.0],
                ['pos_x' => 1400.0, 'pos_y' => 240.0]
            ],
            3 => [ // Level C (Department Heads, 4 nodes)
                ['pos_x' => 320.0, 'pos_y' => 420.0],
                ['pos_x' => 720.0, 'pos_y' => 420.0],
                ['pos_x' => 1200.0, 'pos_y' => 420.0],
                ['pos_x' => 1600.0, 'pos_y' => 420.0]
            ],
            4 => [ // Level D (Operational Officers, 8 nodes)
                ['pos_x' => 220.0, 'pos_y' => 610.0],
                ['pos_x' => 420.0, 'pos_y' => 610.0],
                ['pos_x' => 620.0, 'pos_y' => 610.0],
                ['pos_x' => 820.0, 'pos_y' => 610.0],
                ['pos_x' => 1100.0, 'pos_y' => 610.0],
                ['pos_x' => 1300.0, 'pos_y' => 610.0],
                ['pos_x' => 1500.0, 'pos_y' => 610.0],
                ['pos_x' => 1700.0, 'pos_y' => 610.0]
            ]
        ];

        // Group nodes by tier
        $tiers = [];
        foreach ($nodes as $node) {
            $lvl = (int)$node->tier_level;
            if ($lvl <= 0) $lvl = 1;
            if (!isset($tiers[$lvl])) {
                $tiers[$lvl] = [];
            }
            $tiers[$lvl][] = $node;
        }
        ksort($tiers);

        $nodes_table = $this->db->prefixTable('organogram_nodes');

        foreach ($tiers as $tierNum => $tierNodes) {
            $coords = $dualCoordinates[$tierNum] ?? [];
            foreach ($tierNodes as $idx => $node) {
                if (isset($coords[$idx])) {
                    $x = $coords[$idx]['pos_x'];
                    $y = $coords[$idx]['pos_y'];
                } else {
                    // Overflow nodes positioned neatly
                    $cardWidth = 260;
                    $spacing = 40;
                    $startX = 1800 + ($idx * ($cardWidth + $spacing));
                    $y = 60 + (($tierNum - 1) * 180);
                    $x = $startX;
                }

                $this->db->table($nodes_table)->where('id', $node->id)->update([
                    'pos_x' => (float)$x,
                    'pos_y' => (float)$y,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return true;
    }

    /**
     * Seed initial standard baseline organogram layout for NCS with dual branching hierarchy
     */
    public function seed_standard_ncs_organogram_if_empty() {
        $nodes_table = $this->db->prefixTable('organogram_nodes');
        $count = $this->db->table($nodes_table)->countAllResults();
        if ($count > 0) {
            return;
        }

        $roles_table = $this->db->prefixTable('roles');
        $roles = $this->db->table($roles_table)->where('deleted', 0)->get()->getResult();
        if (empty($roles)) {
            return;
        }

        $roleByTitle = [];
        foreach ($roles as $r) {
            $roleByTitle[$r->title] = $r;
        }

        $getRole = function($term) use ($roles) {
            foreach ($roles as $r) {
                if ($r->title === $term || stripos($r->title, $term) !== false) {
                    return $r;
                }
            }
            return null;
        };

        $dualTree = [
            'A1' => ['term' => 'General Secretary', 'tier' => 1, 'is_apex' => 1, 'limit' => 500000000, 'x' => 960, 'y' => 60, 'parent' => null],
            'B1' => ['term' => 'Head of Finance & Accounting', 'tier' => 2, 'is_apex' => 0, 'limit' => 50000000, 'x' => 520, 'y' => 240, 'parent' => 'A1'],
            'B2' => ['term' => 'Head of Technical & Sports', 'tier' => 2, 'is_apex' => 0, 'limit' => 50000000, 'x' => 1400, 'y' => 240, 'parent' => 'A1'],
            'C1' => ['term' => 'Head of Procurement', 'tier' => 3, 'is_apex' => 0, 'limit' => 20000000, 'x' => 320, 'y' => 420, 'parent' => 'B1'],
            'C2' => ['term' => 'Senior Accountant', 'tier' => 3, 'is_apex' => 0, 'limit' => 15000000, 'x' => 720, 'y' => 420, 'parent' => 'B1'],
            'C3' => ['term' => 'Facilities & Venues Manager', 'tier' => 3, 'is_apex' => 0, 'limit' => 20000000, 'x' => 1200, 'y' => 420, 'parent' => 'B2'],
            'C4' => ['term' => 'Chief Internal Auditor', 'tier' => 3, 'is_apex' => 0, 'limit' => 20000000, 'x' => 1600, 'y' => 420, 'parent' => 'B2'],
            'D1' => ['term' => 'Senior Procurement Officer', 'tier' => 4, 'is_apex' => 0, 'limit' => 5000000, 'x' => 220, 'y' => 610, 'parent' => 'C1'],
            'D2' => ['term' => 'Procurement Officer', 'tier' => 4, 'is_apex' => 0, 'limit' => 2000000, 'x' => 420, 'y' => 610, 'parent' => 'C1'],
            'D3' => ['term' => 'Project Accountant / Grants Officer', 'tier' => 4, 'is_apex' => 0, 'limit' => 5000000, 'x' => 620, 'y' => 610, 'parent' => 'C2'],
            'D4' => ['term' => 'Accounts Assistant / Cashier', 'tier' => 4, 'is_apex' => 0, 'limit' => 1000000, 'x' => 820, 'y' => 610, 'parent' => 'C2'],
            'D5' => ['term' => 'Senior Estate & Facility Supervisor', 'tier' => 4, 'is_apex' => 0, 'limit' => 5000000, 'x' => 1100, 'y' => 610, 'parent' => 'C3'],
            'D6' => ['term' => 'Venue Booking Clerk', 'tier' => 4, 'is_apex' => 0, 'limit' => 1000000, 'x' => 1300, 'y' => 610, 'parent' => 'C3'],
            'D7' => ['term' => 'Senior Sports Officer', 'tier' => 4, 'is_apex' => 0, 'limit' => 5000000, 'x' => 1500, 'y' => 610, 'parent' => 'C4'],
            'D8' => ['term' => 'Maintenance Technician', 'tier' => 4, 'is_apex' => 0, 'limit' => 1000000, 'x' => 1700, 'y' => 610, 'parent' => 'C4']
        ];

        $nodeIdMap = [];
        foreach ($dualTree as $key => $cfg) {
            $r = $getRole($cfg['term']);
            if (!$r) continue;
            $parentKey = $cfg['parent'];
            $parentId = $parentKey && isset($nodeIdMap[$parentKey]) ? $nodeIdMap[$parentKey] : null;

            $nodeId = $this->place_role_on_canvas($r->id, $cfg['x'], $cfg['y'], $parentId);
            if ($nodeId) {
                $nodeIdMap[$key] = $nodeId;
                $this->db->table($nodes_table)->where('id', $nodeId)->update([
                    'tier_level' => $cfg['tier'],
                    'is_apex' => $cfg['is_apex'],
                    'approval_limit_ugx' => $cfg['limit']
                ]);
            }
        }
    }
}
