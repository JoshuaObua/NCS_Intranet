<?php

namespace App\Models;

class Store_grn_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'store_grn';
        parent::__construct($this->table);
        parent::init_activity_log("store_grn", "grn_number", "", "");
    }

    function get_details($options = array()) {
        $grn_table   = $this->db->prefixTable('store_grn');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        $id     = $this->_get_clean_value($options, "id");
        $search = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $grn_table.id=$id";
        }
        if ($search) {
            $where .= " AND ($grn_table.grn_number ILIKE '%$search%'
                OR $grn_table.po_reference ILIKE '%$search%'
                OR $grn_table.supplier_name ILIKE '%$search%')";
        }

        $sql = "SELECT $grn_table.*,
                CONCAT(receiver.first_name,' ',receiver.last_name) AS receiver_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $grn_table
            LEFT JOIN $users_table AS receiver ON receiver.id=$grn_table.received_by
            WHERE $grn_table.deleted=0 $where
            ORDER BY $grn_table.id DESC";

        return $this->db->query($sql);
    }
}
