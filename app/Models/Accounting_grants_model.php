<?php

namespace App\Models;

class Accounting_grants_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'accounting_grants';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $grants_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $grants_table.id=$id";
        }

        $accountability_status = get_array_value($options, "accountability_status");
        if ($accountability_status) {
            $where .= " AND $grants_table.accountability_status='$accountability_status'";
        }

        $sql = "SELECT $grants_table.*
                FROM $grants_table
                WHERE $grants_table.deleted=0 $where
                ORDER BY $grants_table.id DESC";

        return $this->db->query($sql);
    }
}
