<?php

namespace App\Models;

class Store_stock_takes_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'store_stock_takes';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $takes_table = $this->db->prefixTable('store_stock_takes');
        $items_table = $this->db->prefixTable('store_inventory_items');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        $item_id = $this->_get_clean_value($options, "item_id");
        $search  = $this->_get_clean_value($options, "search");

        if ($item_id) {
            $where .= " AND $takes_table.item_id=$item_id";
        }
        if ($search) {
            $where .= " AND ($items_table.item_name ILIKE '%$search%'
                OR $takes_table.reconciliation_notes ILIKE '%$search%')";
        }

        $sql = "SELECT $takes_table.*,
                $items_table.item_name,
                $items_table.sku_code,
                $items_table.unit_cost,
                ($takes_table.variance * $items_table.unit_cost) AS total_variance_value,
                CONCAT(inspector.first_name,' ',inspector.last_name) AS inspector_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $takes_table
            LEFT JOIN $items_table ON $items_table.id=$takes_table.item_id
            LEFT JOIN $users_table AS inspector ON inspector.id=$takes_table.conducted_by
            WHERE 1=1 $where
            ORDER BY $takes_table.id DESC";

        return $this->db->query($sql);
    }
}
