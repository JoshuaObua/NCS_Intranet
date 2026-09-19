<?php

namespace App\Models;

class Store_inventory_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'store_inventory_items';
        parent::__construct($this->table);
        parent::init_activity_log("store_item", "item_name", "", "");
    }

    function get_details($options = array()) {
        $items_table       = $this->db->prefixTable('store_inventory_items');
        $departments_table = $this->db->prefixTable('departments');
        $roles_table       = $this->db->prefixTable('roles');
        $users_table       = $this->db->prefixTable('users');

        $where = "";
        $id         = $this->_get_clean_value($options, "id");
        $category   = $this->_get_clean_value($options, "category");
        $status     = $this->_get_clean_value($options, "status");
        $condition  = $this->_get_clean_value($options, "condition");
        $search     = $this->_get_clean_value($options, "search");
        $low_stock  = $this->_get_clean_value($options, "low_stock");

        if ($id) {
            $where .= " AND $items_table.id=$id";
        }
        if ($category) {
            $where .= " AND $items_table.category='$category'";
        }
        if ($status) {
            $where .= " AND $items_table.status='$status'";
        }
        if ($condition) {
            $where .= " AND $items_table.condition='$condition'";
        }
        if ($low_stock) {
            $where .= " AND $items_table.quantity_on_hand <= $items_table.min_reorder_level";
        }
        if ($search) {
            $where .= " AND ($items_table.sku_code ILIKE '%$search%'
                OR $items_table.item_name ILIKE '%$search%'
                OR $items_table.warehouse_bin_location ILIKE '%$search%')";
        }

        $sql = "SELECT $items_table.*,
                ($items_table.quantity_on_hand * $items_table.unit_cost) AS total_stock_value,
                dept.title AS department_title,
                role.title AS assigned_role_title,
                CONCAT(u.first_name,' ',u.last_name) AS assigned_user_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $items_table
            LEFT JOIN $departments_table AS dept ON dept.id=$items_table.department_id AND dept.deleted=0
            LEFT JOIN $roles_table AS role ON role.id=$items_table.assigned_role_id AND role.deleted=0
            LEFT JOIN $users_table AS u ON u.id=$items_table.assigned_user_id AND u.deleted=0
            WHERE $items_table.deleted=0 $where
            ORDER BY $items_table.id DESC";

        return $this->db->query($sql);
    }

    function get_category_summary() {
        $table = $this->db->prefixTable('store_inventory_items');
        $sql = "SELECT category,
                       COUNT(id) AS total_skus,
                       SUM(quantity_on_hand) AS total_items,
                       COALESCE(SUM(quantity_on_hand * unit_cost), 0) AS category_value
                FROM $table
                WHERE deleted=0
                GROUP BY category";
        return $this->db->query($sql)->getResult();
    }
}
