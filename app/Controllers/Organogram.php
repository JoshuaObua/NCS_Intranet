<?php

namespace App\Controllers;

use App\Libraries\Approval_chain;

/**
 * Organogram & Bottom-to-Top Approval Workflow Controller
 *
 * Manages the interactive organizational chart canvas, drag-and-drop palette,
 * auto-linking connectors, and hierarchical document approval routing.
 */
class Organogram extends Security_Controller {

    private $approvalChain;

    public function __construct() {
        parent::__construct();
        $this->approvalChain = new Approval_chain();
    }

    /**
     * Main Organogram Designer & Visualizer View
     */
    public function index() {
        $view_data['can_manage'] = ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_manage_all_kinds_of_settings"));
        return $this->template->rander("organogram/index", $view_data);
    }

    /**
     * Fetch complete diagram state via AJAX
     * Returns placed canvas nodes, curved connector strings, and unplaced palette items
     */
    public function get_diagram_data() {
        $nodes = $this->Organogram_model->get_placed_nodes();
        $connectors = $this->Organogram_model->get_connectors();
        $palette = $this->Organogram_model->get_unplaced_palette_items();

        return $this->response->setJSON([
            'success' => true,
            'nodes' => $nodes,
            'connectors' => $connectors,
            'palette' => $palette
        ]);
    }

    /**
     * Save node coordinates on canvas drag
     */
    public function save_node_position() {
        if (!$this->can_manage_organogram()) {
            return $this->response->setJSON(['success' => false, 'message' => app_lang('access_denied')]);
        }

        $nodeId = (int)$this->request->getPost('id');
        $posX = (float)$this->request->getPost('pos_x');
        $posY = (float)$this->request->getPost('pos_y');

        if ($nodeId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid node ID.']);
        }

        $this->Organogram_model->update_node_position($nodeId, $posX, $posY);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Drag & Drop: Place an office/role from the right palette onto the canvas
     * Once placed, it is permanently removed from the palette to prevent duplication!
     */
    public function add_node() {
        if (!$this->can_manage_organogram()) {
            return $this->response->setJSON(['success' => false, 'message' => app_lang('access_denied')]);
        }

        $roleId = (int)$this->request->getPost('role_id');
        $posX = (float)$this->request->getPost('pos_x') ?: 200.0;
        $posY = (float)$this->request->getPost('pos_y') ?: 200.0;
        $parentNodeId = $this->request->getPost('parent_node_id') ? (int)$this->request->getPost('parent_node_id') : null;

        if ($roleId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid role ID.']);
        }

        $newNodeId = $this->Organogram_model->place_role_on_canvas($roleId, $posX, $posY, $parentNodeId);

        if (!$newNodeId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to place role on canvas or role already exists.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'node_id' => $newNodeId,
            'message' => 'Office placed on canvas and moved from palette.'
        ]);
    }

    /**
     * Drag & Drop Department: Place an entire branch with all its unplaced offices
     */
    public function add_department() {
        if (!$this->can_manage_organogram()) {
            return $this->response->setJSON(['success' => false, 'message' => app_lang('access_denied')]);
        }

        $deptId = (int)$this->request->getPost('department_id');
        $startX = (float)$this->request->getPost('pos_x') ?: 200.0;
        $startY = (float)$this->request->getPost('pos_y') ?: 250.0;

        if ($deptId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid department ID.']);
        }

        $roles = $this->Roles_model->get_all_where(['department_id' => $deptId, 'deleted' => 0])->getResult();
        $placedCount = 0;
        $offset = 0;

        foreach ($roles as $role) {
            $nodeId = $this->Organogram_model->place_role_on_canvas($role->id, $startX + ($offset * 60), $startY + ($offset * 80));
            if ($nodeId) {
                $placedCount++;
                $offset++;
            }
        }

        $this->Organogram_model->compute_auto_layout();

        return $this->response->setJSON([
            'success' => true,
            'placed_count' => $placedCount,
            'message' => "Placed {$placedCount} branch offices on canvas."
        ]);
    }

    /**
     * Remove node from canvas (Restores the office back to the right palette!)
     */
    public function remove_node() {
        if (!$this->can_manage_organogram()) {
            return $this->response->setJSON(['success' => false, 'message' => app_lang('access_denied')]);
        }

        $nodeId = (int)$this->request->getPost('id');
        if ($nodeId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid node ID.']);
        }

        $success = $this->Organogram_model->remove_node_from_canvas($nodeId);

        return $this->response->setJSON([
            'success' => $success,
            'message' => 'Office removed from canvas and returned to palette.'
        ]);
    }

    /**
     * Connect subordinate office to approving superior office
     */
    public function connect_nodes() {
        if (!$this->can_manage_organogram()) {
            return $this->response->setJSON(['success' => false, 'message' => app_lang('access_denied')]);
        }

        $fromNodeId = (int)$this->request->getPost('from_node_id');
        $toNodeId = (int)$this->request->getPost('to_node_id');
        $workflowType = $this->request->getPost('workflow_type') ?: 'general';

        if ($fromNodeId <= 0 || $toNodeId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Both nodes must be specified.']);
        }

        if ($fromNodeId === $toNodeId) {
            return $this->response->setJSON(['success' => false, 'message' => 'An office cannot report to itself.']);
        }

        if ($this->Organogram_model->would_create_cycle($fromNodeId, $toNodeId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Circular reporting loop detected. Connection rejected.']);
        }

        $this->Organogram_model->set_parent_and_link($fromNodeId, $toNodeId);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Hierarchical approval relationship established.'
        ]);
    }

    /**
     * Disconnect two nodes
     */
    public function disconnect_nodes() {
        if (!$this->can_manage_organogram()) {
            return $this->response->setJSON(['success' => false, 'message' => app_lang('access_denied')]);
        }

        $fromNodeId = (int)$this->request->getPost('from_node_id');
        $toNodeId = (int)$this->request->getPost('to_node_id');

        $this->Organogram_model->unlink_nodes($fromNodeId, $toNodeId);

        return $this->response->setJSON(['success' => true, 'message' => 'Relationship severed.']);
    }

    /**
     * Compute clean hierarchical auto-layout
     */
    public function auto_layout() {
        if (!$this->can_manage_organogram()) {
            return $this->response->setJSON(['success' => false, 'message' => app_lang('access_denied')]);
        }

        $this->Organogram_model->compute_auto_layout();
        return $this->response->setJSON(['success' => true, 'message' => 'Hierarchical layout re-aligned.']);
    }

    /**
     * Reset organogram to standard baseline government hierarchy
     */
    public function reset_hierarchy() {
        if (!$this->can_manage_organogram()) {
            return $this->response->setJSON(['success' => false, 'message' => app_lang('access_denied')]);
        }

        $db = \Config\Database::connect();
        $db->table($db->prefixTable('organogram_connectors'))->emptyTable();
        $db->table($db->prefixTable('organogram_nodes'))->emptyTable();

        $this->Organogram_model->seed_standard_ncs_organogram_if_empty();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Organogram reset to standard NCS governance tree.'
        ]);
    }

    /**
     * Simulate and trace the bottom-to-top approval flow from any office
     */
    public function simulate_workflow() {
        $roleId = (int)$this->request->getPost('role_id');
        $nodeId = (int)$this->request->getPost('node_id');
        $amountUgx = (float)$this->request->getPost('amount_ugx');
        $documentType = $this->request->getPost('document_type') ?: 'general';

        if ($nodeId > 0) {
            $result = $this->Organogram_model->resolve_approval_chain($nodeId, false, $amountUgx);
        } elseif ($roleId > 0) {
            $result = $this->Organogram_model->resolve_approval_chain($roleId, true, $amountUgx);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Please select an initiating office.']);
        }

        return $this->response->setJSON([
            'success' => $result['success'],
            'message' => $result['message'] ?? 'Workflow path resolved.',
            'chain' => $result['chain'] ?? [],
            'initiator' => $result['initiator'] ?? null,
            'total_steps' => $result['total_steps'] ?? 0,
            'document_type' => $documentType,
            'amount_ugx' => $amountUgx
        ]);
    }

    /**
     * Check permission to edit diagram structure
     */
    private function can_manage_organogram(): bool {
        return ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_manage_all_kinds_of_settings"));
    }
}
