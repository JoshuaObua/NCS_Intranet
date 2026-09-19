<?php

namespace App\Models;

class Hr_report_submissions_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'hr_report_submissions';
        parent::__construct($this->table);
        parent::init_activity_log("hr_report_submission", "period_label", "hr_report_template", "template_id");
    }

    function get_details($options = array()) {
        $submissions_table = $this->db->prefixTable('hr_report_submissions');
        $templates_table   = $this->db->prefixTable('hr_report_templates');
        $users_table       = $this->db->prefixTable('users');

        $where       = "";
        $id          = $this->_get_clean_value($options, "id");
        $template_id = $this->_get_clean_value($options, "template_id");
        $department  = $this->_get_clean_value($options, "department");
        $status      = $this->_get_clean_value($options, "status");
        $search      = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $submissions_table.id=$id";
        }
        if ($template_id) {
            $where .= " AND $submissions_table.template_id=$template_id";
        }
        if ($department) {
            $where .= " AND $submissions_table.department='$department'";
        }
        if ($status) {
            $where .= " AND $submissions_table.status='$status'";
        }
        if ($search) {
            $where .= " AND ($submissions_table.period_label ILIKE '%$search%'
                OR $templates_table.title ILIKE '%$search%'
                OR $submissions_table.department ILIKE '%$search%')";
        }

        $sql = "SELECT $submissions_table.*,
                $templates_table.title AS template_title,
                $templates_table.frequency,
                CONCAT($users_table.first_name,' ',$users_table.last_name) AS submitted_by_name,
                CONCAT(approver.first_name,' ',approver.last_name) AS approved_by_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $submissions_table
            LEFT JOIN $templates_table ON $templates_table.id=$submissions_table.template_id
            LEFT JOIN $users_table ON $users_table.id=$submissions_table.submitted_by
            LEFT JOIN $users_table AS approver ON approver.id=$submissions_table.hod_approved_by
            WHERE $submissions_table.deleted=0 $where
            ORDER BY $submissions_table.created_at DESC";

        return $this->db->query($sql);
    }

    function get_dept_summary() {
        $table = $this->db->prefixTable('hr_report_submissions');
        $sql = "SELECT department, status, COUNT(id) AS total FROM $table WHERE deleted=0 GROUP BY department, status ORDER BY department";
        return $this->db->query($sql)->getResult();
    }
}
