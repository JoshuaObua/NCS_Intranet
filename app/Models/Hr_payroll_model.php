<?php

namespace App\Models;

class Hr_payroll_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'hr_payroll';
        parent::__construct($this->table);
        parent::init_activity_log("hr_payroll", "period_month", "team_member", "user_id");
    }

    function get_details($options = array()) {
        $payroll_table  = $this->db->prefixTable('hr_payroll');
        $users_table    = $this->db->prefixTable('users');
        $profiles_table = $this->db->prefixTable('hr_profiles');

        $where   = "";
        $id      = $this->_get_clean_value($options, "id");
        $user_id = $this->_get_clean_value($options, "user_id");
        $year    = $this->_get_clean_value($options, "period_year");
        $month   = $this->_get_clean_value($options, "period_month");
        $status  = $this->_get_clean_value($options, "status");
        $search  = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $payroll_table.id=$id";
        }
        if ($user_id) {
            $where .= " AND $payroll_table.user_id=$user_id";
        }
        if ($year) {
            $where .= " AND $payroll_table.period_year=$year";
        }
        if ($month) {
            $where .= " AND $payroll_table.period_month=$month";
        }
        if ($status) {
            $where .= " AND $payroll_table.status='$status'";
        }
        if ($search) {
            $where .= " AND ($users_table.first_name ILIKE '%$search%' OR $users_table.last_name ILIKE '%$search%')";
        }

        $sql = "SELECT $payroll_table.*,
                CONCAT($users_table.first_name,' ',$users_table.last_name) AS full_name,
                $users_table.email,
                $users_table.job_title,
                $users_table.image,
                $profiles_table.staff_id,
                $profiles_table.department,
                $profiles_table.bank_name,
                $profiles_table.bank_account_number,
                $profiles_table.bank_account_name,
                CONCAT(approver.first_name,' ',approver.last_name) AS approved_by_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $payroll_table
            LEFT JOIN $users_table ON $users_table.id=$payroll_table.user_id AND $users_table.deleted=0
            LEFT JOIN $profiles_table ON $profiles_table.user_id=$payroll_table.user_id AND $profiles_table.deleted=0
            LEFT JOIN $users_table AS approver ON approver.id=$payroll_table.approved_by
            WHERE $payroll_table.deleted=0 $where
            ORDER BY $payroll_table.period_year DESC, $payroll_table.period_month DESC, $users_table.first_name ASC";

        return $this->db->query($sql);
    }

    function get_period_summary($year, $month) {
        $table = $this->db->prefixTable('hr_payroll');
        $sql = "SELECT COUNT(id) AS total_employees,
                COALESCE(SUM(gross_salary),0) AS total_gross,
                COALESCE(SUM(net_salary),0) AS total_net,
                COALESCE(SUM(paye_tax),0) AS total_paye,
                COALESCE(SUM(nssf_employer),0) AS total_nssf_employer
                FROM $table WHERE deleted=0 AND period_year=$year AND period_month=$month";
        return $this->db->query($sql)->getRow();
    }

    function exists_for_period($user_id, $year, $month) {
        $table = $this->db->prefixTable('hr_payroll');
        $sql = "SELECT id FROM $table WHERE user_id=$user_id AND period_year=$year AND period_month=$month AND deleted=0 LIMIT 1";
        return $this->db->query($sql)->getRow();
    }

    // Uganda URA PAYE calculation (monthly gross in UGX)
    static function calculate_paye($gross_monthly) {
        $gross = (float)$gross_monthly;
        if ($gross <= 235000) return 0;
        if ($gross <= 335000) return round(($gross - 235000) * 0.10, 2);
        if ($gross <= 410000) return round(10000 + ($gross - 335000) * 0.20, 2);
        $paye = 10000 + 15000 + ($gross - 410000) * 0.30;
        if ($gross > 10000000) {
            $paye += $gross * 0.10;
        }
        return round($paye, 2);
    }
}
