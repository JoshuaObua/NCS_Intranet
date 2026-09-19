<?php

namespace App\Models;

class Ict_equipment_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'ict_equipment';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $equipment_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $equipment_table.id=$id";
        }

        $category = get_array_value($options, "category");
        if ($category) {
            $where .= " AND $equipment_table.category='$category'";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $equipment_table.status='$status'";
        }

        $sql = "SELECT $equipment_table.*
                FROM $equipment_table
                WHERE $equipment_table.deleted=0 $where
                ORDER BY $equipment_table.id DESC";

        return $this->db->query($sql);
    }
}
