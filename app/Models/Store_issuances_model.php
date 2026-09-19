<?php

namespace App\Models;

class Store_issuances_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'store_issuances';
        parent::__construct($this->table);
        parent::init_activity_log("store_issuance", "siv_number", "", "");
    }

    function get_details($options = array()) {
        $issuances_table   = $this->db->prefixTable('store_issuances');
        $items_table       = $this->db->prefixTable('store_inventory_items');
        $departments_table = $this->db->prefixTable('departments');
        $roles_table       = $this->db->prefixTable('roles');
        $users_table       = $this->db->prefixTable('users');

        $where = "";
        $id            = $this->_get_clean_value($options, "id");
        $item_id       = $this->_get_clean_value($options, "item_id");
        $department_id = $this->_get_clean_value($options, "department_id");
        $search        = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $issuances_table.id=$id";
        }
        if ($item_id) {
            $where .= " AND $issuances_table.item_id=$item_id";
        }
        if ($department_id) {
            $where .= " AND $issuances_table.issued_to_department_id=$department_id";
        }
        if ($search) {
            $where .= " AND ($issuances_table.siv_number ILIKE '%$search%'
                OR $items_table.item_name ILIKE '%$search%'
                OR $issuances_table.handover_notes ILIKE '%$search%')";
        }

        $sql = "SELECT $issuances_table.*,
                $items_table.item_name,
                $items_table.sku_code,
                dept.title AS issued_to_department_title,
                role.title AS issued_to_role_title,
                CONCAT(target_user.first_name,' ',target_user.last_name) AS issued_to_user_name,
                CONCAT(issuer.first_name,' ',issuer.last_name) AS issued_by_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $issuances_table
            LEFT JOIN $items_table ON $items_table.id=$issuances_table.item_id
            LEFT JOIN $departments_table AS dept ON dept.id=$issuances_table.issued_to_department_id AND dept.deleted=0
            LEFT JOIN $roles_table AS role ON role.id=$issuances_table.issued_to_role_id AND role.deleted=0
            LEFT JOIN $users_table AS target_user ON target_user.id=$issuances_table.issued_to_user_id AND target_user.deleted=0
            LEFT JOIN $users_table AS issuer ON issuer.id=$issuances_table.issued_by
            WHERE $issuances_table.deleted=0 $where
            ORDER BY $issuances_table.id DESC";

        return $this->db->query($sql);
    }
}
