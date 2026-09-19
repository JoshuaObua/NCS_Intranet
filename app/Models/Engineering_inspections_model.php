<?php

namespace App\Models;

class Engineering_inspections_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'engineering_inspections';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $inspect_table = $this->table;
        $users_table   = $this->db->prefixTable('users');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $inspect_table.id=$id";
        }

        $sql = "SELECT $inspect_table.*, 
                       CONCAT($users_table.first_name, ' ', $users_table.last_name) AS inspector_name
                FROM $inspect_table
                LEFT JOIN $users_table ON $users_table.id = $inspect_table.inspector_user_id
                WHERE $inspect_table.deleted=0 $where
                ORDER BY $inspect_table.id DESC";

        return $this->db->query($sql);
    }
}
