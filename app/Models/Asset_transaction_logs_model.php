<?php

namespace App\Models;

class Asset_transaction_logs_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'asset_transaction_logs';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $logs_table = $this->table;
        $assets_table = 'fixed_assets';
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $logs_table.id=$id";
        }

        $asset_id = get_array_value($options, "asset_id");
        if ($asset_id) {
            $where .= " AND $logs_table.asset_id=$asset_id";
        }

        $transaction_type = get_array_value($options, "transaction_type");
        if ($transaction_type) {
            $where .= " AND $logs_table.transaction_type='$transaction_type'";
        }

        $sql = "SELECT $logs_table.*, $assets_table.asset_number, $assets_table.tag_number, $assets_table.asset_description
                FROM $logs_table
                LEFT JOIN $assets_table ON $assets_table.id = $logs_table.asset_id
                WHERE 1=1 $where
                ORDER BY $logs_table.id DESC";

        return $this->db->query($sql);
    }
}
