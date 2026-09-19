<?php

namespace App\Models;

class Accounting_reconciliations_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'accounting_reconciliations';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $reconciliations_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $reconciliations_table.id=$id";
        }

        $sql = "SELECT $reconciliations_table.*
                FROM $reconciliations_table
                WHERE $reconciliations_table.deleted=0 $where
                ORDER BY $reconciliations_table.id DESC";

        return $this->db->query($sql);
    }
}
