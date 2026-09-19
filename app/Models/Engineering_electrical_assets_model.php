<?php

namespace App\Models;

class Engineering_electrical_assets_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'engineering_electrical_assets';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $electrical_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $electrical_table.id=$id";
        }

        $equipment_type = get_array_value($options, "equipment_type");
        if ($equipment_type) {
            $where .= " AND $electrical_table.equipment_type='$equipment_type'";
        }

        $sql = "SELECT $electrical_table.*
                FROM $electrical_table
                WHERE $electrical_table.deleted=0 $where
                ORDER BY $electrical_table.id DESC";

        return $this->db->query($sql);
    }
}
