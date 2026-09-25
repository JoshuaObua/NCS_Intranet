<?php

namespace App\Libraries;

use App\Models\Organogram_model;

/**
 * Universal Approval Chain Engine
 * Routes requests and documents bottom-to-top according to the organogram hierarchy.
 */
class Approval_chain {

    private $organogramModel;
    private $db;

    public function __construct() {
        $this->organogramModel = model("App\Models\Organogram_model");
        $this->db = \Config\Database::connect();
    }

    /**
     * Resolve the bottom-to-top approval chain for a specific user
     */
    public function get_chain_for_user($user_id, $amount = 0, $workflow_type = 'general') {
        $users_table = $this->db->prefixTable('users');
        $user = $this->db->table($users_table)->where('id', $user_id)->get()->getRow();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found.',
                'chain' => []
            ];
        }

        if (empty($user->role_id)) {
            return [
                'success' => false,
                'message' => 'User does not possess an assigned role/office.',
                'chain' => []
            ];
        }

        return $this->organogramModel->resolve_approval_chain($user->role_id, true, $amount);
    }

    /**
     * Dispatch a document into the organogram approval pipeline
     */
    public function initiate_approval($module, $record_id, $requester_id, $amount = 0) {
        $chainResult = $this->get_chain_for_user($requester_id, $amount);
        if (!$chainResult['success'] || empty($chainResult['chain'])) {
            return [
                'success' => false,
                'message' => $chainResult['message'] ?? 'Could not resolve approval sequence from organogram.'
            ];
        }

        $chain = $chainResult['chain'];
        $workflows_table = $this->db->prefixTable('approval_workflows');

        // Check if an active workflow already exists for this document
        $existing = $this->db->table($workflows_table)
            ->where('module', $module)
            ->where('record_id', $record_id)
            ->where('status', 'pending')
            ->get()->getRow();

        if ($existing) {
            return [
                'success' => true,
                'workflow_id' => $existing->id,
                'status' => 'pending',
                'current_step' => $existing->current_step,
                'message' => 'Workflow already in progress.'
            ];
        }

        $startNodeId = $chainResult['initiator']['node_id'];
        $firstStep = $chain[0];

        $history = [
            [
                'step' => 0,
                'action' => 'initiated',
                'user_id' => $requester_id,
                'timestamp' => date('Y-m-d H:i:s'),
                'remarks' => 'Submitted for hierarchical organogram approval'
            ]
        ];

        $data = [
            'module' => $module,
            'record_id' => (int)$record_id,
            'requester_id' => (int)$requester_id,
            'start_node_id' => (int)$startNodeId,
            'current_node_id' => (int)$firstStep['node_id'],
            'status' => 'pending',
            'current_step' => 1,
            'total_steps' => count($chain),
            'step_history' => json_encode($history),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table($workflows_table)->insert($data);
        $workflowId = $this->db->insertID();

        return [
            'success' => true,
            'workflow_id' => $workflowId,
            'current_approver_office' => $firstStep['office_title'],
            'total_steps' => count($chain),
            'chain' => $chain
        ];
    }

    /**
     * Process an approval or rejection step
     */
    public function process_step($workflow_id, $actor_user_id, $action = 'approved', $remarks = '') {
        $workflows_table = $this->db->prefixTable('approval_workflows');
        $workflow = $this->db->table($workflows_table)->where('id', $workflow_id)->get()->getRow();

        if (!$workflow) {
            return ['success' => false, 'message' => 'Workflow not found.'];
        }

        if ($workflow->status !== 'pending') {
            return ['success' => false, 'message' => "Workflow has already been finalized ({$workflow->status})."];
        }

        $history = json_decode($workflow->step_history, true) ?: [];
        $currentStepNum = (int)$workflow->current_step;
        $totalSteps = (int)$workflow->total_steps;

        if ($action === 'rejected') {
            $history[] = [
                'step' => $currentStepNum,
                'action' => 'rejected',
                'user_id' => $actor_user_id,
                'timestamp' => date('Y-m-d H:i:s'),
                'remarks' => $remarks ?: 'Rejected by approving authority'
            ];

            $this->db->table($workflows_table)->where('id', $workflow_id)->update([
                'status' => 'rejected',
                'step_history' => json_encode($history),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return ['success' => true, 'status' => 'rejected', 'is_final' => true];
        }

        // Action is approved
        $history[] = [
            'step' => $currentStepNum,
            'action' => 'approved',
            'user_id' => $actor_user_id,
            'timestamp' => date('Y-m-d H:i:s'),
            'remarks' => $remarks ?: 'Approved and recommended upward'
        ];

        if ($currentStepNum >= $totalSteps) {
            // Apex / Final level reached!
            $this->db->table($workflows_table)->where('id', $workflow_id)->update([
                'status' => 'approved',
                'step_history' => json_encode($history),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return [
                'success' => true,
                'status' => 'approved',
                'is_final' => true,
                'message' => 'Final approval granted by apex authority.'
            ];
        }

        // Advance to next step upward in organogram
        $nextStepNum = $currentStepNum + 1;
        $chainResult = $this->get_chain_for_user($workflow->requester_id);
        $chain = $chainResult['chain'];
        $nextNodeId = isset($chain[$nextStepNum - 1]) ? $chain[$nextStepNum - 1]['node_id'] : $workflow->current_node_id;

        $this->db->table($workflows_table)->where('id', $workflow_id)->update([
            'current_step' => $nextStepNum,
            'current_node_id' => $nextNodeId,
            'step_history' => json_encode($history),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return [
            'success' => true,
            'status' => 'pending',
            'is_final' => false,
            'next_step' => $nextStepNum,
            'total_steps' => $totalSteps,
            'next_office' => $chain[$nextStepNum - 1]['office_title'] ?? 'Next Approver'
        ];
    }
}
