<?php

namespace App\Models;

class Procurement_suppliers_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'procurement_suppliers';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $suppliers_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $suppliers_table.id=$id";
        }

        $is_blacklisted = get_array_value($options, "is_blacklisted");
        if ($is_blacklisted !== null && $is_blacklisted !== "") {
            $val = $is_blacklisted ? "TRUE" : "FALSE";
            $where .= " AND $suppliers_table.is_blacklisted=$val";
        }

        $sql = "SELECT $suppliers_table.*
                FROM $suppliers_table
                WHERE $suppliers_table.deleted=0 $where
                ORDER BY $suppliers_table.company_name ASC";

        return $this->db->query($sql);
    }
}
