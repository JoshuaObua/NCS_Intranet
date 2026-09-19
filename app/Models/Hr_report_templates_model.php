<?php

namespace App\Models;

class Hr_report_templates_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'hr_report_templates';
        parent::__construct($this->table);
        parent::init_activity_log("hr_report_template", "title", "", "");
    }

    function get_details($options = array()) {
        $templates_table = $this->db->prefixTable('hr_report_templates');
        $users_table     = $this->db->prefixTable('users');

        $where      = "";
        $id         = $this->_get_clean_value($options, "id");
        $department = $this->_get_clean_value($options, "department");
        $search     = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $templates_table.id=$id";
        }
        if ($department && $department !== 'ALL') {
            $where .= " AND ($templates_table.department='$department' OR $templates_table.department='ALL')";
        }
        if ($search) {
            $where .= " AND ($templates_table.title ILIKE '%$search%' OR $templates_table.department ILIKE '%$search%')";
        }

        $sql = "SELECT $templates_table.*,
                CONCAT($users_table.first_name,' ',$users_table.last_name) AS created_by_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $templates_table
            LEFT JOIN $users_table ON $users_table.id=$templates_table.created_by
            WHERE $templates_table.deleted=0 $where
            ORDER BY $templates_table.title ASC";

        return $this->db->query($sql);
    }

    function get_active_for_dropdown($department = 'ALL') {
        $table = $this->db->prefixTable('hr_report_templates');
        $dept_cond = $department && $department !== 'ALL'
            ? "AND (department='$department' OR department='ALL')"
            : "";
        $sql = "SELECT id, title FROM $table WHERE deleted=0 AND is_active=1 $dept_cond ORDER BY title ASC";
        return $this->db->query($sql)->getResult();
    }
}
