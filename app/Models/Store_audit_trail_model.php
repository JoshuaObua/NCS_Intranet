<?php

namespace App\Models;

class Store_audit_trail_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'store_audit_trail';
        parent::__construct($this->table);
    }

    function log_action($item_id, $action_type, $quantity, $from_loc, $to_loc, $reported_by, $details) {
        $data = array(
            "item_id"       => (int)$item_id,
            "action_type"   => $action_type,
            "quantity"      => (int)$quantity,
            "from_location" => $from_loc,
            "to_location"   => $to_loc,
            "reported_by"   => (int)$reported_by,
            "details"       => $details,
            "created_at"    => get_current_utc_time(),
        );
        return $this->ci_save($data);
    }

    function get_details($options = array()) {
        $audit_table = $this->db->prefixTable('store_audit_trail');
        $items_table = $this->db->prefixTable('store_inventory_items');
        $users_table = $this->db->prefixTable('users');

        $where = "";
        $item_id     = $this->_get_clean_value($options, "item_id");
        $action_type = $this->_get_clean_value($options, "action_type");
        $search      = $this->_get_clean_value($options, "search");

        if ($item_id) {
            $where .= " AND $audit_table.item_id=$item_id";
        }
        if ($action_type) {
            $where .= " AND $audit_table.action_type='$action_type'";
        }
        if ($search) {
            $where .= " AND ($items_table.item_name ILIKE '%$search%'
                OR $audit_table.action_type ILIKE '%$search%'
                OR $audit_table.details ILIKE '%$search%')";
        }

        $sql = "SELECT $audit_table.*,
                $items_table.item_name,
                $items_table.sku_code,
                CONCAT(reporter.first_name,' ',reporter.last_name) AS reporter_name,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $audit_table
            LEFT JOIN $items_table ON $items_table.id=$audit_table.item_id
            LEFT JOIN $users_table AS reporter ON reporter.id=$audit_table.reported_by
            WHERE 1=1 $where
            ORDER BY $audit_table.id DESC";

        return $this->db->query($sql);
    }
}
