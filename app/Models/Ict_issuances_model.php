<?php

namespace App\Models;

class Ict_issuances_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'ict_issuances';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $issuances_table = $this->table;
        $equipment_table = $this->db->prefixTable('ict_equipment');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $issuances_table.id=$id";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $issuances_table.status='$status'";
        }

        $sql = "SELECT $issuances_table.*, $equipment_table.item_name, $equipment_table.item_code, $equipment_table.category AS equipment_category
                FROM $issuances_table
                LEFT JOIN $equipment_table ON $equipment_table.id = $issuances_table.equipment_id
                WHERE $issuances_table.deleted=0 $where
                ORDER BY $issuances_table.id DESC";

        return $this->db->query($sql);
    }
}
