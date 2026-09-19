<?php

namespace App\Models;

class Audit_reports_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'audit_reports';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $reports_table = $this->table;
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $reports_table.id=$id";
        }

        $report_code = get_array_value($options, "report_code");
        if ($report_code) {
            $where .= " AND $reports_table.report_code='$report_code'";
        }

        $financial_year = get_array_value($options, "financial_year");
        if ($financial_year) {
            $where .= " AND $reports_table.financial_year='$financial_year'";
        }

        $sql = "SELECT $reports_table.*
                FROM $reports_table
                WHERE $reports_table.deleted=0 $where
                ORDER BY $reports_table.id DESC";

        return $this->db->query($sql);
    }
}
