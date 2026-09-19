<?php

namespace App\Models;

class Ict_expenses_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'ict_expenses';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $expenses_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $expenses_table.id=$id";
        }

        $category = get_array_value($options, "category");
        if ($category) {
            $where .= " AND $expenses_table.category='$category'";
        }

        $sql = "SELECT $expenses_table.*
                FROM $expenses_table
                WHERE $expenses_table.deleted=0 $where
                ORDER BY $expenses_table.id DESC";

        return $this->db->query($sql);
    }
}
