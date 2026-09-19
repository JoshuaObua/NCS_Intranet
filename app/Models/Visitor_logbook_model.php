<?php

namespace App\Models;

class Visitor_logbook_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'visitor_logbook';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $visitor_logbook_table = $this->db->prefixTable('visitor_logbook');
        $users_table = $this->db->prefixTable('users');
        $roles_table = $this->db->prefixTable('roles');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $visitor_logbook_table.id=$id";
        }

        $nationality = $this->_get_clean_value($options, "nationality");
        if ($nationality) {
            $where .= " AND $visitor_logbook_table.nationality='$nationality'";
        }

        $status = $this->_get_clean_value($options, "status");
        if ($status) {
            $where .= " AND $visitor_logbook_table.status='$status'";
        }

        $gate_name = $this->_get_clean_value($options, "gate_name");
        if ($gate_name) {
            $where .= " AND $visitor_logbook_table.gate_name='$gate_name'";
        }

        $to_user_id = $this->_get_clean_value($options, "to_user_id");
        if ($to_user_id) {
            $where .= " AND $visitor_logbook_table.to_user_id=$to_user_id";
        }

        $recorded_by = $this->_get_clean_value($options, "recorded_by");
        if ($recorded_by) {
            $where .= " AND $visitor_logbook_table.recorded_by=$recorded_by";
        }

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($visitor_logbook_table.time_in BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')";
        }

        $sql = "SELECT $visitor_logbook_table.*,
                CONCAT(host_user.first_name, ' ', host_user.last_name) AS to_user_name,
                host_user.email AS to_user_email,
                host_user.job_title AS to_user_job_title,
                host_role.title AS to_user_role,
                CONCAT(recorder.first_name, ' ', recorder.last_name) AS recorded_by_user,
                CONCAT(checkout_user.first_name, ' ', checkout_user.last_name) AS checked_out_by_user,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $visitor_logbook_table
            LEFT JOIN $users_table AS host_user ON host_user.id = $visitor_logbook_table.to_user_id
            LEFT JOIN $roles_table AS host_role ON host_role.id = host_user.role_id
            LEFT JOIN $users_table AS recorder ON recorder.id = $visitor_logbook_table.recorded_by
            LEFT JOIN $users_table AS checkout_user ON checkout_user.id = $visitor_logbook_table.checked_out_by
            WHERE $visitor_logbook_table.deleted=0 $where
            ORDER BY $visitor_logbook_table.id DESC";

        return $this->db->query($sql);
    }
}
