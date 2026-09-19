<?php

namespace App\Models;

class Procurement_form5_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'procurement_form5';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $form5_table = $this->table;
        $users_table = $this->db->prefixTable('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $form5_table.id=$id";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $form5_table.status='$status'";
        }

        $procurement_type = get_array_value($options, "procurement_type");
        if ($procurement_type) {
            $where .= " AND $form5_table.procurement_type='$procurement_type'";
        }

        $sql = "SELECT $form5_table.*, 
                       CONCAT($users_table.first_name, ' ', $users_table.last_name) AS requester_name,
                       $users_table.job_title AS requester_title
                FROM $form5_table
                LEFT JOIN $users_table ON $users_table.id = $form5_table.requester_user_id
                WHERE $form5_table.deleted=0 $where
                ORDER BY $form5_table.id DESC";

        return $this->db->query($sql);
    }

    function get_summary_stats() {
        $form5_table = $this->table;
        $sql = "SELECT 
                    COUNT(id) AS total_requests,
                    SUM(CASE WHEN status = 'SUBMITTED' THEN 1 ELSE 0 END) AS pending_hod,
                    SUM(CASE WHEN status = 'HOD_CONFIRMED' THEN 1 ELSE 0 END) AS pending_vote,
                    SUM(CASE WHEN status = 'VOTE_CLEARED' THEN 1 ELSE 0 END) AS pending_gs,
                    SUM(CASE WHEN status = 'APPROVED' THEN 1 ELSE 0 END) AS approved_count,
                    COALESCE(SUM(grand_total_estimated_cost), 0) AS total_value
                FROM $form5_table
                WHERE deleted=0";
        return $this->db->query($sql)->getRow();
    }
}
