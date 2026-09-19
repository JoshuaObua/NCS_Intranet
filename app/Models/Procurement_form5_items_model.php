<?php

namespace App\Models;

class Procurement_form5_items_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'procurement_form5_items';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $items_table = $this->table;

        $where = "";
        $form5_id = get_array_value($options, "form5_id");
        if ($form5_id) {
            $where .= " AND $items_table.form5_id=$form5_id";
        }

        $sql = "SELECT $items_table.*
                FROM $items_table
                WHERE $items_table.deleted=0 $where
                ORDER BY $items_table.item_no ASC";

        return $this->db->query($sql);
    }
}
