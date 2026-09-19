<?php

namespace App\Models;

class Procurement_plans_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'procurement_plans';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $plans_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $plans_table.id=$id";
        }

        $financial_year = get_array_value($options, "financial_year");
        if ($financial_year) {
            $where .= " AND $plans_table.financial_year='$financial_year'";
        }

        $sql = "SELECT $plans_table.*
                FROM $plans_table
                WHERE $plans_table.deleted=0 $where
                ORDER BY $plans_table.id DESC";

        return $this->db->query($sql);
    }
}
