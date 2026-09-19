<?php

namespace App\Models;

class Legal_litigation_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'legal_litigation';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $litigation_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $litigation_table.id=$id";
        }

        $sql = "SELECT $litigation_table.*
                FROM $litigation_table
                WHERE $litigation_table.deleted=0 $where
                ORDER BY $litigation_table.id DESC";

        return $this->db->query($sql);
    }
}
