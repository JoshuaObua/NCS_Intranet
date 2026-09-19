<?php

namespace App\Models;

class Legal_disputes_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'legal_disputes';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $disputes_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $disputes_table.id=$id";
        }

        $case_status = get_array_value($options, "case_status");
        if ($case_status) {
            $where .= " AND $disputes_table.case_status='$case_status'";
        }

        $sql = "SELECT $disputes_table.*
                FROM $disputes_table
                WHERE $disputes_table.deleted=0 $where
                ORDER BY $disputes_table.id DESC";

        return $this->db->query($sql);
    }
}
