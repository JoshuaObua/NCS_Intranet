<?php

namespace App\Models;

class Hr_profiles_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'hr_profiles';
        parent::__construct($this->table);
        parent::init_activity_log("hr_profile", "staff_id", "team_member", "user_id");
    }

    function get_details($options = array()) {
        $profiles_table = $this->db->prefixTable('hr_profiles');
        $users_table    = $this->db->prefixTable('users');

        $where      = "";
        $id         = $this->_get_clean_value($options, "id");
        $user_id    = $this->_get_clean_value($options, "user_id");
        $staff_id   = $this->_get_clean_value($options, "staff_id");
        $department = $this->_get_clean_value($options, "department");
        $search     = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $profiles_table.id=$id";
        }
        if ($user_id) {
            $where .= " AND $profiles_table.user_id=$user_id";
        }
        if ($staff_id) {
            $where .= " AND $profiles_table.staff_id ILIKE '%$staff_id%'";
        }
        if ($department) {
            $where .= " AND $profiles_table.department='$department'";
        }
        if ($search) {
            $where .= " AND ($users_table.first_name ILIKE '%$search%'
                OR $users_table.last_name ILIKE '%$search%'
                OR $profiles_table.staff_id ILIKE '%$search%'
                OR $profiles_table.nin_number ILIKE '%$search%'
                OR $profiles_table.department ILIKE '%$search%')";
        }

        $sql = "SELECT $profiles_table.*,
                CONCAT($users_table.first_name,' ',$users_table.last_name) AS full_name,
                $users_table.email,
                $users_table.job_title,
                $users_table.image,
                $users_table.status AS user_status,
                CONCAT(sup.first_name,' ',sup.last_name) AS supervisor_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $profiles_table
            LEFT JOIN $users_table ON $users_table.id=$profiles_table.user_id AND $users_table.deleted=0
            LEFT JOIN $users_table AS sup ON sup.id=$profiles_table.direct_supervisor_id
            WHERE $profiles_table.deleted=0 $where
            ORDER BY $users_table.first_name ASC";

        return $this->db->query($sql);
    }

    function get_profile_by_user($user_id) {
        $table = $this->db->prefixTable('hr_profiles');
        $sql = "SELECT * FROM $table WHERE user_id=$user_id AND deleted=0 LIMIT 1";
        return $this->db->query($sql)->getRow();
    }

    function get_departments() {
        $table = $this->db->prefixTable('hr_profiles');
        $sql = "SELECT DISTINCT department FROM $table WHERE deleted=0 AND department IS NOT NULL AND department != '' ORDER BY department";
        return $this->db->query($sql)->getResult();
    }

    function save_profile($data, $user_id) {
        $table = $this->db->prefixTable('hr_profiles');
        $exists = $this->get_profile_by_user($user_id);
        if ($exists && $exists->id) {
            return $this->ci_save($data, $exists->id);
        } else {
            $data['user_id'] = $user_id;
            return $this->ci_save($data);
        }
    }
}
