<?php

namespace App\Models;

class Facilities_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'facilities';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $facilities_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $facilities_table.id=$id";
        }

        $category = get_array_value($options, "category");
        if ($category) {
            $where .= " AND $facilities_table.category='$category'";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $facilities_table.status='$status'";
        }

        $sql = "SELECT $facilities_table.*
                FROM $facilities_table
                WHERE $facilities_table.deleted=0 $where
                ORDER BY $facilities_table.id ASC";

        return $this->db->query($sql);
    }
}
