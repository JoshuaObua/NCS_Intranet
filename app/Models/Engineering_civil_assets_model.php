<?php

namespace App\Models;

class Engineering_civil_assets_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'engineering_civil_assets';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $civil_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $civil_table.id=$id";
        }

        $boundary_status = get_array_value($options, "boundary_status");
        if ($boundary_status) {
            $where .= " AND $civil_table.boundary_status='$boundary_status'";
        }

        $sql = "SELECT $civil_table.*
                FROM $civil_table
                WHERE $civil_table.deleted=0 $where
                ORDER BY $civil_table.id DESC";

        return $this->db->query($sql);
    }
}
