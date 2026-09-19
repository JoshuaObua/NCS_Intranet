<?php

namespace App\Models;

class Engineering_technicians_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'engineering_technicians';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $technicians_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $technicians_table.id=$id";
        }

        $trade = get_array_value($options, "trade");
        if ($trade) {
            $where .= " AND $technicians_table.trade='$trade'";
        }

        $duty_status = get_array_value($options, "duty_status");
        if ($duty_status) {
            $where .= " AND $technicians_table.duty_status='$duty_status'";
        }

        $sql = "SELECT $technicians_table.*
                FROM $technicians_table
                WHERE $technicians_table.deleted=0 $where
                ORDER BY $technicians_table.id DESC";

        return $this->db->query($sql);
    }
}
