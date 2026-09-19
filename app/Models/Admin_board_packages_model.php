<?php

namespace App\Models;

class Admin_board_packages_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'admin_board_packages';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $packages_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $packages_table.id=$id";
        }

        $board_quarter = get_array_value($options, "board_quarter");
        if ($board_quarter) {
            $where .= " AND $packages_table.board_quarter='$board_quarter'";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $packages_table.status='$status'";
        }

        $security_level = get_array_value($options, "security_level");
        if ($security_level) {
            $where .= " AND $packages_table.security_level='$security_level'";
        }

        $sql = "SELECT $packages_table.*
                FROM $packages_table
                WHERE $packages_table.deleted=0 $where
                ORDER BY $packages_table.id DESC";

        return $this->db->query($sql);
    }
}
