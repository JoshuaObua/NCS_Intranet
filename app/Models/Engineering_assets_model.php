<?php

namespace App\Models;

class Engineering_assets_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'engineering_assets';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $assets_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $assets_table.id=$id";
        }

        $condition_rating = get_array_value($options, "condition_rating");
        if ($condition_rating) {
            $where .= " AND $assets_table.condition_rating='$condition_rating'";
        }

        $sql = "SELECT $assets_table.*
                FROM $assets_table
                WHERE $assets_table.deleted=0 $where
                ORDER BY $assets_table.id DESC";

        return $this->db->query($sql);
    }
}
