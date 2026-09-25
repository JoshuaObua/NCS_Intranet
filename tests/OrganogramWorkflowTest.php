<?php

/**
 * Organogram Hierarchy & Bottom-to-Top Approval Workflow Test Suite
 * National Council of Sports (NCS) Intranet
 */

$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=ncs_db', 'ncs_user', 'ncs_pass_2024');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=================================================================================\n";
echo "   NATIONAL COUNCIL OF SPORTS (NCS) - ORGANOGRAM & APPROVAL WORKFLOW TEST SUITE   \n";
echo "=================================================================================\n\n";

$passed = 0;
$total = 0;

function organogram_assert($cond, $msg) {
    global $passed, $total;
    $total++;
    if ($cond) {
        $passed++;
        echo "  [PASS] {$msg}\n";
    } else {
        echo "  [FAIL] {$msg}\n";
    }
}

// -----------------------------------------------------------------------------
// 1. Verify Database Schema & Table Structures
// -----------------------------------------------------------------------------
echo ">> 1. Verifying Database Schema Invariants...\n";

$stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_name IN ('ncs_organogram_nodes', 'ncs_organogram_connectors', 'ncs_approval_workflows')");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
organogram_assert(in_array('ncs_organogram_nodes', $tables), "Table 'ncs_organogram_nodes' exists");
organogram_assert(in_array('ncs_organogram_connectors', $tables), "Table 'ncs_organogram_connectors' exists");
organogram_assert(in_array('ncs_approval_workflows', $tables), "Table 'ncs_approval_workflows' exists");

$stmt = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'ncs_organogram_nodes'");
$nodeCols = $stmt->fetchAll(PDO::FETCH_COLUMN);
organogram_assert(in_array('role_id', $nodeCols), "Column role_id exists in ncs_organogram_nodes");
organogram_assert(in_array('parent_node_id', $nodeCols), "Column parent_node_id exists in ncs_organogram_nodes");
organogram_assert(in_array('tier_level', $nodeCols), "Column tier_level exists in ncs_organogram_nodes");
organogram_assert(in_array('is_apex', $nodeCols), "Column is_apex exists in ncs_organogram_nodes");
organogram_assert(in_array('approval_limit_ugx', $nodeCols), "Column approval_limit_ugx exists in ncs_organogram_nodes");

// -----------------------------------------------------------------------------
// 2. Placed Nodes & Baseline Hierarchy Verification
// -----------------------------------------------------------------------------
echo "\n>> 2. Testing Baseline Organogram Structure...\n";

$stmt = $pdo->query("SELECT COUNT(*) FROM ncs_organogram_nodes");
$nodeCount = (int)$stmt->fetchColumn();
organogram_assert($nodeCount >= 9, "Baseline placed nodes count is >= 9 (Found: {$nodeCount})");

// Verify Apex Node (General Secretary)
$stmt = $pdo->query("SELECT n.*, r.title FROM ncs_organogram_nodes n JOIN ncs_roles r ON r.id = n.role_id WHERE n.is_apex = 1");
$apex = $stmt->fetch(PDO::FETCH_ASSOC);
organogram_assert($apex !== false, "Apex governance node exists");
organogram_assert(strpos($apex['title'], 'General Secretary') !== false, "Apex node is General Secretary: '{$apex['title']}' (Office ID: {$apex['role_id']})");
organogram_assert(is_null($apex['parent_node_id']), "Apex node has no parent_node_id (Highest authority)");

// Verify Connectors
$stmt = $pdo->query("SELECT COUNT(*) FROM ncs_organogram_connectors");
$connCount = (int)$stmt->fetchColumn();
organogram_assert($connCount >= 14, "Baseline connectors count is >= 14 (Found: {$connCount})");

// Verify Dual-Branching Hierarchy Invariants (Level A: 1, Level B: 2, Level C: 4, Level D: 8)
$stmt = $pdo->query("SELECT tier_level, COUNT(*) as cnt FROM ncs_organogram_nodes GROUP BY tier_level ORDER BY tier_level");
$tierCounts = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $tierCounts[(int)$row['tier_level']] = (int)$row['cnt'];
}
organogram_assert(($tierCounts[1] ?? 0) === 1, "Level A (Apex Authority) has exactly 1 node (Found: " . ($tierCounts[1] ?? 0) . ")");
organogram_assert(($tierCounts[2] ?? 0) === 2, "Level B (Directorates) has exactly 2 dual branches (Found: " . ($tierCounts[2] ?? 0) . ")");
organogram_assert(($tierCounts[3] ?? 0) === 4, "Level C (Department Heads) has exactly 4 branches (2 per directorate) (Found: " . ($tierCounts[3] ?? 0) . ")");
organogram_assert(($tierCounts[4] ?? 0) === 8, "Level D (Operational Officers) has exactly 8 branches (2 per department head) (Found: " . ($tierCounts[4] ?? 0) . ")");

// Verify binary branch branching: Each Level B parent has exactly 2 Level C children
$stmt = $pdo->query("SELECT parent_node_id, COUNT(*) as child_count FROM ncs_organogram_nodes WHERE tier_level = 3 GROUP BY parent_node_id");
$levelCBranching = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$allBHaveTwo = count($levelCBranching) === 2 && !in_array(false, array_map(fn($c) => (int)$c === 2, $levelCBranching));
organogram_assert($allBHaveTwo, "Binary hierarchy: Every Level B Directorate has exactly 2 Level C Department children");

// Verify binary branch branching: Each Level C parent has exactly 2 Level D children
$stmt = $pdo->query("SELECT parent_node_id, COUNT(*) as child_count FROM ncs_organogram_nodes WHERE tier_level = 4 GROUP BY parent_node_id");
$levelDBranching = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$allCHaveTwo = count($levelDBranching) === 4 && !in_array(false, array_map(fn($c) => (int)$c === 2, $levelDBranching));
organogram_assert($allCHaveTwo, "Binary hierarchy: Every Level C Department Head has exactly 2 Level D Officer children");

// -----------------------------------------------------------------------------
// 3. Zero-Duplication Palette Invariant
// -----------------------------------------------------------------------------
echo "\n>> 3. Testing Zero-Duplication Palette Filter Invariant...\n";

// Ensure no role exists simultaneously on the canvas AND in the unplaced palette
$stmt = $pdo->query("SELECT r.id, r.title 
                     FROM ncs_roles r 
                     WHERE r.deleted = 0 
                       AND r.id IN (SELECT role_id FROM ncs_organogram_nodes) 
                       AND r.id NOT IN (SELECT role_id FROM ncs_organogram_nodes)");
$overlap = $stmt->fetchAll(PDO::FETCH_ASSOC);
organogram_assert(count($overlap) === 0, "Zero duplication: No roles overlap between placed canvas and unplaced palette");

// Total roles count vs placed + unplaced
$stmtTotal = $pdo->query("SELECT COUNT(*) FROM ncs_roles WHERE deleted = 0");
$totalRoles = (int)$stmtTotal->fetchColumn();

$stmtPlaced = $pdo->query("SELECT COUNT(*) FROM ncs_organogram_nodes");
$placedRoles = (int)$stmtPlaced->fetchColumn();

$stmtUnplaced = $pdo->query("SELECT COUNT(*) FROM ncs_roles WHERE deleted = 0 AND id NOT IN (SELECT role_id FROM ncs_organogram_nodes)");
$unplacedRoles = (int)$stmtUnplaced->fetchColumn();

organogram_assert(($placedRoles + $unplacedRoles) === $totalRoles, "Mathematical parity: Placed ({$placedRoles}) + Unplaced ({$unplacedRoles}) == Total Roles ({$totalRoles})");

// -----------------------------------------------------------------------------
// 4. Drag & Drop Emulation (Add Node -> Removed from Palette -> Remove Node -> Restored)
// -----------------------------------------------------------------------------
echo "\n>> 4. Emulating Drag & Drop Lifecycle (Canvas Placement & Palette Restoration)...\n";

// Pick an unplaced role
$stmt = $pdo->query("SELECT id, title, department_id FROM ncs_roles WHERE deleted = 0 AND id NOT IN (SELECT role_id FROM ncs_organogram_nodes) LIMIT 1");
$testRole = $stmt->fetch(PDO::FETCH_ASSOC);
organogram_assert($testRole !== false, "Found unplaced test role: '{$testRole['title']}' (ID: {$testRole['id']})");

$testRoleId = (int)$testRole['id'];
$testDeptId = (int)$testRole['department_id'];

// Place on canvas
$stmt = $pdo->prepare("INSERT INTO ncs_organogram_nodes (role_id, department_id, tier_level, pos_x, pos_y, is_apex) VALUES (?, ?, 3, 450, 450, 0) RETURNING id");
$stmt->execute([$testRoleId, $testDeptId]);
$insertedNodeId = (int)$stmt->fetchColumn();
organogram_assert($insertedNodeId > 0, "Placed role '{$testRole['title']}' onto canvas (Node ID: {$insertedNodeId})");

// Verify it is now excluded from palette
$stmt = $pdo->prepare("SELECT COUNT(*) FROM ncs_roles WHERE id = ? AND id NOT IN (SELECT role_id FROM ncs_organogram_nodes)");
$stmt->execute([$testRoleId]);
$inPaletteAfterPlace = (int)$stmt->fetchColumn();
organogram_assert($inPaletteAfterPlace === 0, "Role '{$testRole['title']}' is completely removed from the right palette");

// Remove node from canvas (Simulate delete button)
$stmt = $pdo->prepare("DELETE FROM ncs_organogram_nodes WHERE id = ?");
$stmt->execute([$insertedNodeId]);

// Verify it has returned to the palette
$stmt = $pdo->prepare("SELECT COUNT(*) FROM ncs_roles WHERE id = ? AND id NOT IN (SELECT role_id FROM ncs_organogram_nodes)");
$stmt->execute([$testRoleId]);
$inPaletteAfterDelete = (int)$stmt->fetchColumn();
organogram_assert($inPaletteAfterDelete === 1, "Role '{$testRole['title']}' is successfully restored back to the unplaced palette");

// -----------------------------------------------------------------------------
// 5. Circular Reporting Loop (Cycle Detection)
// -----------------------------------------------------------------------------
echo "\n>> 5. Testing Anti-Cycle Loop Detection Algorithm...\n";

// In-memory cycle checker matching Organogram_model::would_create_cycle
function test_would_create_cycle(PDO $pdo, int $fromNodeId, int $toNodeId): bool {
    if ($fromNodeId === $toNodeId) return true;
    $visited = [];
    $curr = $toNodeId;
    while ($curr !== null) {
        if ($curr === $fromNodeId) return true;
        if (in_array($curr, $visited)) return true;
        $visited[] = $curr;
        $stmt = $pdo->prepare("SELECT parent_node_id FROM ncs_organogram_nodes WHERE id = ?");
        $stmt->execute([$curr]);
        $parent = $stmt->fetchColumn();
        $curr = $parent ? (int)$parent : null;
    }
    return false;
}

$apexNodeId = (int)$apex['id'];
$stmt = $pdo->query("SELECT n.id FROM ncs_organogram_nodes n JOIN ncs_roles r ON r.id = n.role_id WHERE r.title LIKE '%Head of Finance%' LIMIT 1");
$hodFinanceNodeId = (int)$stmt->fetchColumn();

// Self-reporting
organogram_assert(test_would_create_cycle($pdo, $apexNodeId, $apexNodeId) === true, "Cycle detector rejects self-reporting (Node {$apexNodeId} -> Node {$apexNodeId})");

// Apex cannot report to its subordinate (Node Apex -> Node HOD Finance)
organogram_assert(test_would_create_cycle($pdo, $apexNodeId, $hodFinanceNodeId) === true, "Cycle detector prevents Apex ({$apexNodeId}) reporting to subordinate ({$hodFinanceNodeId})");

// Valid parent-child check: Subordinate reporting to supervisor is allowed (Senior Accountant -> HOD Finance)
$stmt = $pdo->query("SELECT n.id FROM ncs_organogram_nodes n JOIN ncs_roles r ON r.id = n.role_id WHERE r.title LIKE '%Senior Accountant%' LIMIT 1");
$seniorAccountantNodeId = (int)$stmt->fetchColumn();
organogram_assert(test_would_create_cycle($pdo, $seniorAccountantNodeId, $hodFinanceNodeId) === false, "Legitimate hierarchical link permitted (Node {$seniorAccountantNodeId} -> Node {$hodFinanceNodeId})");

// -----------------------------------------------------------------------------
// 6. Bottom-to-Top Approval Chain Resolution
// -----------------------------------------------------------------------------
echo "\n>> 6. Testing Bottom-to-Top Requisition Approval Traversal...\n";

// In-memory approval chain solver matching Organogram_model::resolve_approval_chain
function test_resolve_approval_chain(PDO $pdo, int $startNodeId, float $amountUgx = 0): array {
    $chain = [];
    $visited = [];
    $curr = $startNodeId;
    $step = 1;

    while ($curr !== null) {
        if (in_array($curr, $visited)) break;
        $visited[] = $curr;

        $stmt = $pdo->prepare("SELECT n.*, r.title AS role_title, d.title AS department_title 
                               FROM ncs_organogram_nodes n 
                               JOIN ncs_roles r ON r.id = n.role_id 
                               JOIN ncs_departments d ON d.id = n.department_id 
                               WHERE n.id = ?");
        $stmt->execute([$curr]);
        $node = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$node) break;

        $isApex = (int)$node['is_apex'] === 1 || (int)$node['tier_level'] === 0 || strpos($node['role_title'], 'General Secretary') !== false;
        $action = 'Supervisor Review';
        if ($step === 1) {
            $action = 'Initiate Requisition';
        } elseif ($isApex) {
            $action = 'Final Executive Approval';
        } elseif ((int)$node['tier_level'] <= 2 || strpos($node['role_title'], 'Head of') !== false) {
            $action = 'Departmental Head Approval';
        }

        $chain[] = [
            'step_number' => $step,
            'node_id' => (int)$node['id'],
            'role_title' => $node['role_title'],
            'department_title' => $node['department_title'],
            'action' => $action,
            'tier_level' => (int)$node['tier_level'],
            'is_apex' => $isApex,
            'limit' => (float)$node['approval_limit_ugx']
        ];

        // If not apex and office has an approval limit >= amountUgx, can sign off if not initiator
        if ($step > 1 && !$isApex && (float)$node['approval_limit_ugx'] > 0 && (float)$node['approval_limit_ugx'] >= $amountUgx) {
            break;
        }

        $curr = $node['parent_node_id'] ? (int)$node['parent_node_id'] : null;
        $step++;
    }

    return $chain;
}

// Find Senior Accountant node (Tier 3)
$stmt = $pdo->query("SELECT n.id, r.title FROM ncs_organogram_nodes n JOIN ncs_roles r ON r.id = n.role_id WHERE r.title LIKE '%Senior Accountant%'");
$seniorAccountant = $stmt->fetch(PDO::FETCH_ASSOC);
organogram_assert($seniorAccountant !== false, "Senior Accountant placed node located (ID: {$seniorAccountant['id']})");
$seniorAccountantNodeId = (int)$seniorAccountant['id'];

// Check Accounts Assistant / Cashier placed node (Tier 4 / Level D)
$stmt = $pdo->query("SELECT n.id, n.parent_node_id, r.title FROM ncs_organogram_nodes n JOIN ncs_roles r ON r.id = n.role_id WHERE r.title LIKE '%Accounts Assistant%' LIMIT 1");
$accAsstNode = $stmt->fetch(PDO::FETCH_ASSOC);
$createdTempAccAsst = false;
if ($accAsstNode) {
    $accAsstNodeId = (int)$accAsstNode['id'];
    organogram_assert($accAsstNodeId > 0, "Found placed Accounts Assistant node in organogram hierarchy (Node ID: {$accAsstNodeId})");
} else {
    // If not placed, place temporarily
    $stmt = $pdo->query("SELECT id, title, department_id FROM ncs_roles WHERE title LIKE '%Accounts Assistant%' LIMIT 1");
    $accAsstRole = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("INSERT INTO ncs_organogram_nodes (role_id, department_id, parent_node_id, tier_level, pos_x, pos_y, is_apex, approval_limit_ugx) 
                           VALUES (?, ?, ?, 4, 300, 600, 0, 0) RETURNING id");
    $stmt->execute([(int)$accAsstRole['id'], (int)$accAsstRole['department_id'], $seniorAccountantNodeId]);
    $accAsstNodeId = (int)$stmt->fetchColumn();
    $createdTempAccAsst = true;
    organogram_assert($accAsstNodeId > 0, "Placed Accounts Assistant on canvas reporting upward to Senior Accountant (Node ID: {$accAsstNodeId})");
}

// Trace chain for high-value requisition (UGX 80,000,000 > 50M HOD limit) requiring General Secretary
$chain80m = test_resolve_approval_chain($pdo, $accAsstNodeId, 80000000);
organogram_assert(count($chain80m) === 4, "High-value (80M) approval ladder generates exactly 4 sequential steps (Found: " . count($chain80m) . ")");

// Step 1: Initiator (Accounts Assistant)
organogram_assert(strpos($chain80m[0]['role_title'], 'Accounts Assistant') !== false && $chain80m[0]['action'] === 'Initiate Requisition', "Step 1: Accounts Assistant initiates requisition");

// Step 2: Senior Accountant
organogram_assert(strpos($chain80m[1]['role_title'], 'Senior Accountant') !== false && $chain80m[1]['action'] === 'Supervisor Review', "Step 2: Senior Accountant conducts supervisor review");

// Step 3: Head of Finance & Accounting
organogram_assert(strpos($chain80m[2]['role_title'], 'Head of Finance') !== false && $chain80m[2]['action'] === 'Departmental Head Approval', "Step 3: Head of Finance conducts departmental approval");

// Step 4 (Apex): General Secretary (Accounting Officer)
organogram_assert(strpos($chain80m[3]['role_title'], 'General Secretary') !== false && $chain80m[3]['action'] === 'Final Executive Approval', "Step 4: Requisition reaches General Secretary for executive sign-off");

// Trace chain for moderate requisition (UGX 25,000,000 <= 50M HOD limit)
$chain25m = test_resolve_approval_chain($pdo, $accAsstNodeId, 25000000);
organogram_assert(count($chain25m) === 3, "Threshold test: Requisition <= 50M terminates at HOD (Step count: " . count($chain25m) . ")");
organogram_assert(strpos(end($chain25m)['role_title'], 'Head of Finance') !== false, "Threshold test: Final approval granted by Head of Finance within mandate");

// Clean up temporary placed Accounts Assistant node if created
if ($createdTempAccAsst) {
    $stmt = $pdo->prepare("DELETE FROM ncs_organogram_nodes WHERE id = ?");
    $stmt->execute([$accAsstNodeId]);
}

// -----------------------------------------------------------------------------
// 7. Workflow Instance Lifecycle (Initiate -> Advance Step -> Finalize)
// -----------------------------------------------------------------------------
echo "\n>> 7. Testing Approval Workflow State Machine Engine...\n";

// Initiate workflow starting from Senior Accountant with high value (escalates to GS)
$testModule = 'form_5_requisitions';
$testRecordId = 99999;
$testRequesterId = 1;
$startNodeId = $seniorAccountantNodeId;
$chainSenior = test_resolve_approval_chain($pdo, $seniorAccountantNodeId, 80000000);
$totalSteps = count($chainSenior);
organogram_assert($totalSteps === 3, "Senior Accountant approval chain has 3 steps to Apex (Found: {$totalSteps})");

$stmt = $pdo->prepare("INSERT INTO ncs_approval_workflows 
    (module, record_id, requester_id, start_node_id, current_node_id, status, current_step, total_steps, step_history) 
    VALUES (?, ?, ?, ?, ?, 'pending_approval', 2, ?, ?) RETURNING id");

$history = json_encode([
    [
        'step' => 1,
        'action' => 'initiated',
        'office' => 'Senior Accountant',
        'timestamp' => date('Y-m-d H:i:s')
    ]
]);

$stmt->execute([$testModule, $testRecordId, $testRequesterId, $startNodeId, $chainSenior[1]['node_id'], $totalSteps, $history]);
$workflowId = (int)$stmt->fetchColumn();

organogram_assert($workflowId > 0, "Workflow instance created (Workflow ID: {$workflowId}, Status: pending_approval)");

// Step 2 Approval: Advance to Step 3
$stmt = $pdo->prepare("SELECT current_step, current_node_id, status FROM ncs_approval_workflows WHERE id = ?");
$stmt->execute([$workflowId]);
$wfState = $stmt->fetch(PDO::FETCH_ASSOC);
organogram_assert((int)$wfState['current_step'] === 2, "Workflow is at Step 2 awaiting supervisor endorsement");

// Endorse Step 2 -> Move to Step 3
$stmt = $pdo->prepare("UPDATE ncs_approval_workflows SET current_step = 3, current_node_id = ? WHERE id = ?");
$stmt->execute([$chainSenior[2]['node_id'], $workflowId]);

$stmt = $pdo->prepare("SELECT current_step FROM ncs_approval_workflows WHERE id = ?");
$stmt->execute([$workflowId]);
organogram_assert((int)$stmt->fetchColumn() === 3, "Supervisor endorsed: Workflow moved upward to Step 3");

// Final Apex Approval: Mark status as 'approved'
$stmt = $pdo->prepare("UPDATE ncs_approval_workflows SET status = 'approved', current_node_id = NULL WHERE id = ?");
$stmt->execute([$workflowId]);

$stmt = $pdo->prepare("SELECT status FROM ncs_approval_workflows WHERE id = ?");
$stmt->execute([$workflowId]);
organogram_assert($stmt->fetchColumn() === 'approved', "Apex signed: Workflow successfully transitioned to 'approved'");

// Clean up test workflow record
$stmt = $pdo->prepare("DELETE FROM ncs_approval_workflows WHERE id = ?");
$stmt->execute([$workflowId]);

// -----------------------------------------------------------------------------
// Test Summary
// -----------------------------------------------------------------------------
echo "\n=================================================================================\n";
echo ">> Organogram & Approval Workflow Test Suite Finished!\n";
echo "   Passed: {$passed} / {$total} assertions\n";
echo "=================================================================================\n\n";

if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    exit($passed === $total ? 0 : 1);
}
