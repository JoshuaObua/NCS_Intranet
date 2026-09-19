<?php

namespace App\Models;

class Legal_contracts_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'legal_contracts';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $contracts_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $contracts_table.id=$id";
        }

        $contract_type = get_array_value($options, "contract_type");
        if ($contract_type) {
            $where .= " AND $contracts_table.contract_type='$contract_type'";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $contracts_table.status='$status'";
        }

        $sql = "SELECT $contracts_table.*
                FROM $contracts_table
                WHERE $contracts_table.deleted=0 $where
                ORDER BY $contracts_table.id DESC";

        return $this->db->query($sql);
    }
}
