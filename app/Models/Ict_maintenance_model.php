<?php

namespace App\Models;

class Ict_maintenance_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'ict_maintenance_requisitions';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $maint_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $maint_table.id=$id";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $maint_table.status='$status'";
        }

        $sql = "SELECT $maint_table.*
                FROM $maint_table
                WHERE $maint_table.deleted=0 $where
                ORDER BY $maint_table.id DESC";

        return $this->db->query($sql);
    }
}
