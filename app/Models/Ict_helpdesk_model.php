<?php

namespace App\Models;

class Ict_helpdesk_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'ict_helpdesk_tickets';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $tickets_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $tickets_table.id=$id";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $tickets_table.status='$status'";
        }

        $sql = "SELECT $tickets_table.*
                FROM $tickets_table
                WHERE $tickets_table.deleted=0 $where
                ORDER BY $tickets_table.id DESC";

        return $this->db->query($sql);
    }
}
