<?php

namespace App\Models;

class Legal_trademarks_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'legal_trademarks';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $trademarks_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $trademarks_table.id=$id";
        }

        $sql = "SELECT $trademarks_table.*
                FROM $trademarks_table
                WHERE $trademarks_table.deleted=0 $where
                ORDER BY $trademarks_table.id DESC";

        return $this->db->query($sql);
    }
}
