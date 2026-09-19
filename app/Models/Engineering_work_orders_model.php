<?php

namespace App\Models;

class Engineering_work_orders_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'engineering_work_orders';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $wo_table    = $this->table;
        $users_table = $this->db->prefixTable('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $wo_table.id=$id";
        }

        $category = get_array_value($options, "category");
        if ($category) {
            $where .= " AND $wo_table.category='$category'";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $wo_table.status='$status'";
        }

        $sql = "SELECT $wo_table.*, 
                       CONCAT(req.first_name, ' ', req.last_name) AS requester_name,
                       CONCAT(ass.first_name, ' ', ass.last_name) AS assigned_name
                FROM $wo_table
                LEFT JOIN $users_table req ON req.id = $wo_table.requested_by
                LEFT JOIN $users_table ass ON ass.id = $wo_table.assigned_to
                WHERE $wo_table.deleted=0 $where
                ORDER BY $wo_table.id DESC";

        return $this->db->query($sql);
    }
}
