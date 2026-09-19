<?php

namespace App\Models;

class Audit_discrepancies_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'audit_discrepancies';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $disc_table = $this->table;
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $disc_table.id=$id";
        }

        $discrepancy_code = get_array_value($options, "discrepancy_code");
        if ($discrepancy_code) {
            $where .= " AND $disc_table.discrepancy_code='$discrepancy_code'";
        }

        $entity_type = get_array_value($options, "entity_type");
        if ($entity_type) {
            $where .= " AND $disc_table.entity_type='$entity_type'";
        }

        $severity = get_array_value($options, "severity");
        if ($severity) {
            $where .= " AND $disc_table.severity='$severity'";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $disc_table.status='$status'";
        }

        $sql = "SELECT $disc_table.*
                FROM $disc_table
                WHERE $disc_table.deleted=0 $where
                ORDER BY $disc_table.id DESC";

        return $this->db->query($sql);
    }

    function get_audit_summary() {
        $disc_table = $this->table;
        $sql = "SELECT 
                    COUNT(*) as total_discrepancies,
                    COUNT(CASE WHEN status='OPEN' THEN 1 END) as open_count,
                    COUNT(CASE WHEN status='UNDER_REVIEW' THEN 1 END) as review_count,
                    COUNT(CASE WHEN status='RESOLVED' THEN 1 END) as resolved_count,
                    COALESCE(SUM(financial_impact_ugx), 0) as total_impact_ugx
                FROM $disc_table
                WHERE deleted=0";
        return $this->db->query($sql)->getRow();
    }
}
