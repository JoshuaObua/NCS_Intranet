<?php

namespace App\Models;

class Visitor_appointments_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'visitor_appointments';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $visitor_appointments_table = $this->db->prefixTable('visitor_appointments');
        $users_table = $this->db->prefixTable('users');
        $roles_table = $this->db->prefixTable('roles');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $visitor_appointments_table.id=$id";
        }

        $appointment_type = $this->_get_clean_value($options, "appointment_type");
        if ($appointment_type) {
            $where .= " AND $visitor_appointments_table.appointment_type='$appointment_type'";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $visitor_appointments_table.status='$status'";
        }

        $to_user_id = $this->_get_clean_value($options, "to_user_id");
        if ($to_user_id) {
            $where .= " AND $visitor_appointments_table.to_user_id=$to_user_id";
        }

        $created_by = $this->_get_clean_value($options, "created_by");
        if ($created_by) {
            $where .= " AND $visitor_appointments_table.created_by=$created_by";
        }

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($visitor_appointments_table.appointment_date BETWEEN '$start_date' AND '$end_date')";
        }

        $sql = "SELECT $visitor_appointments_table.*,
                CONCAT(creator.first_name, ' ', creator.last_name) AS created_by_user,
                CONCAT(host_user.first_name, ' ', host_user.last_name) AS to_user_name,
                host_user.email AS to_user_email,
                host_user.job_title AS to_user_job_title,
                host_role.title AS to_user_role,
                CONCAT(status_user.first_name, ' ', status_user.last_name) AS status_by_user,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $visitor_appointments_table
            LEFT JOIN $users_table AS creator ON creator.id = $visitor_appointments_table.created_by
            LEFT JOIN $users_table AS host_user ON host_user.id = $visitor_appointments_table.to_user_id
            LEFT JOIN $roles_table AS host_role ON host_role.id = host_user.role_id
            LEFT JOIN $users_table AS status_user ON status_user.id = $visitor_appointments_table.status_by
            WHERE $visitor_appointments_table.deleted=0 $where
            ORDER BY $visitor_appointments_table.id DESC";

        return $this->db->query($sql);
    }
}
