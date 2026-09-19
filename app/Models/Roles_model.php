<?php

namespace App\Models;

class Roles_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'roles';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $roles_table       = $this->db->prefixTable('roles');
        $departments_table = $this->db->prefixTable('departments');

        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where = " AND $roles_table.id=$id";
        }

        $sql = "SELECT $roles_table.*,
                $departments_table.title AS department_title
        FROM $roles_table
        LEFT JOIN $departments_table ON $departments_table.id=$roles_table.department_id AND $departments_table.deleted=0
        WHERE $roles_table.deleted=0 $where
        ORDER BY $roles_table.department_id ASC, $roles_table.rank ASC, $roles_table.title ASC";
        return $this->db->query($sql);
    }

}
