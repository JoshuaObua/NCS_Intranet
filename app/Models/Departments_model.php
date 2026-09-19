<?php

namespace App\Models;

class Departments_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'departments';
        parent::__construct($this->table);
        parent::init_activity_log("department", "title", "", "");
    }

    function get_details($options = array()) {
        $departments_table = $this->db->prefixTable('departments');
        $users_table       = $this->db->prefixTable('users');
        $roles_table       = $this->db->prefixTable('roles');

        $where = "";
        $id     = $this->_get_clean_value($options, "id");
        $search = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $departments_table.id=$id";
        }
        if ($search) {
            $where .= " AND ($departments_table.title ILIKE '%$search%'
                OR $departments_table.code ILIKE '%$search%'
                OR $departments_table.description ILIKE '%$search%')";
        }

        $sql = "SELECT $departments_table.*,
                CONCAT(head.first_name,' ',head.last_name) AS head_name,
                (SELECT COUNT(r.id) FROM $roles_table r WHERE r.department_id=$departments_table.id AND r.deleted=0) AS roles_count,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $departments_table
            LEFT JOIN $users_table AS head ON head.id=$departments_table.head_id
            WHERE $departments_table.deleted=0 $where
            ORDER BY $departments_table.title ASC";

        return $this->db->query($sql);
    }

    function get_department_dropdown() {
        $departments_table = $this->db->prefixTable('departments');
        $sql = "SELECT id, title FROM $departments_table WHERE deleted=0 ORDER BY title ASC";
        $list = $this->db->query($sql)->getResult();
        $dropdown = array("" => "- " . app_lang("select_department") . " -");
        foreach ($list as $row) {
            $dropdown[$row->id] = $row->title;
        }
        return $dropdown;
    }
}
