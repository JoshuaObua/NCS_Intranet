<?php

namespace App\Models;

class Engineering_capex_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'engineering_capex';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $capex_table = $this->table;
        $users_table = $this->db->prefixTable('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $capex_table.id=$id";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $capex_table.status='$status'";
        }

        $sql = "SELECT $capex_table.*, 
                       CONCAT($users_table.first_name, ' ', $users_table.last_name) AS requester_name,
                       $users_table.job_title AS requester_title
                FROM $capex_table
                LEFT JOIN $users_table ON $users_table.id = $capex_table.requested_by
                WHERE $capex_table.deleted=0 $where
                ORDER BY $capex_table.id DESC";

        return $this->db->query($sql);
    }
}
