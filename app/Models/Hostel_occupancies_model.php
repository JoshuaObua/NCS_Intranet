<?php

namespace App\Models;

class Hostel_occupancies_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'hostel_occupancies';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $hostel_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $hostel_table.id=$id";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $hostel_table.status='$status'";
        }

        $sql = "SELECT $hostel_table.*
                FROM $hostel_table
                WHERE $hostel_table.deleted=0 $where
                ORDER BY $hostel_table.id DESC";

        return $this->db->query($sql);
    }
}
