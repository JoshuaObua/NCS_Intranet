<?php

namespace App\Models;

class Accounting_ledgers_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'accounting_ledgers';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $ledgers_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $ledgers_table.id=$id";
        }

        $vote_head_code = get_array_value($options, "vote_head_code");
        if ($vote_head_code) {
            $where .= " AND $ledgers_table.vote_head_code='$vote_head_code'";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $ledgers_table.status='$status'";
        }

        $sql = "SELECT $ledgers_table.*
                FROM $ledgers_table
                WHERE $ledgers_table.deleted=0 $where
                ORDER BY $ledgers_table.id DESC";

        return $this->db->query($sql);
    }
}
