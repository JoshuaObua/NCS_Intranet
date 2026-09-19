<?php

namespace App\Models;

class Store_requisitions_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'store_requisitions';
        parent::__construct($this->table);
        parent::init_activity_log("store_requisition", "req_number", "", "");
    }

    function get_details($options = array()) {
        $reqs_table        = $this->db->prefixTable('store_requisitions');
        $departments_table = $this->db->prefixTable('departments');
        $users_table       = $this->db->prefixTable('users');

        $where = "";
        $id            = $this->_get_clean_value($options, "id");
        $status        = $this->_get_clean_value($options, "status");
        $department_id = $this->_get_clean_value($options, "department_id");
        $search        = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $reqs_table.id=$id";
        }
        if ($status) {
            $where .= " AND $reqs_table.status='$status'";
        }
        if ($department_id) {
            $where .= " AND $reqs_table.department_id=$department_id";
        }
        if ($search) {
            $where .= " AND ($reqs_table.req_number ILIKE '%$search%'
                OR $reqs_table.reason ILIKE '%$search%'
                OR $reqs_table.target_office ILIKE '%$search%')";
        }

        $sql = "SELECT $reqs_table.*,
                dept.title AS department_title,
                CONCAT(req_by.first_name,' ',req_by.last_name) AS requested_by_name,
                CONCAT(hod.first_name,' ',hod.last_name) AS hod_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $reqs_table
            LEFT JOIN $departments_table AS dept ON dept.id=$reqs_table.department_id AND dept.deleted=0
            LEFT JOIN $users_table AS req_by ON req_by.id=$reqs_table.requested_by
            LEFT JOIN $users_table AS hod ON hod.id=$reqs_table.hod_approved_by
            WHERE $reqs_table.deleted=0 $where
            ORDER BY $reqs_table.id DESC";

        return $this->db->query($sql);
    }
}
