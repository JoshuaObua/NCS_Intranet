<?php

namespace App\Models;

class Hr_appraisals_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'hr_appraisals';
        parent::__construct($this->table);
        parent::init_activity_log("hr_appraisal", "period_name", "team_member", "employee_id");
    }

    function get_details($options = array()) {
        $appraisals_table = $this->db->prefixTable('hr_appraisals');
        $users_table      = $this->db->prefixTable('users');

        $where       = "";
        $id          = $this->_get_clean_value($options, "id");
        $employee_id = $this->_get_clean_value($options, "employee_id");
        $supervisor_id = $this->_get_clean_value($options, "supervisor_id");
        $status      = $this->_get_clean_value($options, "status");
        $year        = $this->_get_clean_value($options, "period_year");
        $search      = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $appraisals_table.id=$id";
        }
        if ($employee_id) {
            $where .= " AND $appraisals_table.employee_id=$employee_id";
        }
        if ($supervisor_id) {
            $where .= " AND $appraisals_table.supervisor_id=$supervisor_id";
        }
        if ($status) {
            $where .= " AND $appraisals_table.status='$status'";
        }
        if ($year) {
            $where .= " AND $appraisals_table.period_year=$year";
        }
        if ($search) {
            $where .= " AND ($appraisals_table.period_name ILIKE '%$search%'
                OR emp.first_name ILIKE '%$search%' OR emp.last_name ILIKE '%$search%')";
        }

        $sql = "SELECT $appraisals_table.*,
                CONCAT(emp.first_name,' ',emp.last_name) AS employee_name,
                emp.email AS employee_email,
                emp.job_title AS employee_job_title,
                emp.image AS employee_image,
                CONCAT(sup.first_name,' ',sup.last_name) AS supervisor_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $appraisals_table
            LEFT JOIN $users_table AS emp ON emp.id=$appraisals_table.employee_id AND emp.deleted=0
            LEFT JOIN $users_table AS sup ON sup.id=$appraisals_table.supervisor_id
            WHERE $appraisals_table.deleted=0 $where
            ORDER BY $appraisals_table.period_year DESC, emp.first_name ASC";

        return $this->db->query($sql);
    }

    function get_pending_count($supervisor_id) {
        $table = $this->db->prefixTable('hr_appraisals');
        $sql = "SELECT COUNT(id) AS total FROM $table WHERE deleted=0 AND supervisor_id=$supervisor_id AND status='self_submitted'";
        $row = $this->db->query($sql)->getRow();
        return $row ? (int)$row->total : 0;
    }
}
