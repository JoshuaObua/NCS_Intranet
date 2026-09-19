<?php

namespace App\Models;

class Admin_approvals_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'admin_approvals';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $approvals_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $approvals_table.id=$id";
        }

        $approval_type = get_array_value($options, "approval_type");
        if ($approval_type) {
            $where .= " AND $approvals_table.approval_type='$approval_type'";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $approvals_table.status='$status'";
        }

        $urgency = get_array_value($options, "urgency");
        if ($urgency) {
            $where .= " AND $approvals_table.urgency='$urgency'";
        }

        $sql = "SELECT $approvals_table.*
                FROM $approvals_table
                WHERE $approvals_table.deleted=0 $where
                ORDER BY $approvals_table.id DESC";

        return $this->db->query($sql);
    }
}
