<?php

namespace App\Models;

class Accounting_vote_clearance_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'accounting_vote_clearance';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $clearance_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $clearance_table.id=$id";
        }

        $clearance_status = get_array_value($options, "clearance_status");
        if ($clearance_status) {
            $where .= " AND $clearance_table.clearance_status='$clearance_status'";
        }

        $sql = "SELECT $clearance_table.*
                FROM $clearance_table
                WHERE $clearance_table.deleted=0 $where
                ORDER BY $clearance_table.id DESC";

        return $this->db->query($sql);
    }
}
