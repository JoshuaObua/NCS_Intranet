<?php

namespace App\Models;

class Fixed_assets_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'fixed_assets';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $assets_table = $this->table;
        $where = "";

        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $assets_table.id=$id";
        }

        $asset_number = get_array_value($options, "asset_number");
        if ($asset_number) {
            $where .= " AND $assets_table.asset_number='$asset_number'";
        }

        $tag_number = get_array_value($options, "tag_number");
        if ($tag_number) {
            $where .= " AND $assets_table.tag_number='$tag_number'";
        }

        $category_segment1 = get_array_value($options, "category_segment1");
        if ($category_segment1) {
            $where .= " AND $assets_table.category_segment1='$category_segment1'";
        }

        $category_segment3 = get_array_value($options, "category_segment3");
        if ($category_segment3) {
            $where .= " AND $assets_table.category_segment3='$category_segment3'";
        }

        $status = get_array_value($options, "status");
        if ($status) {
            $where .= " AND $assets_table.status='$status'";
        }

        $verification_status = get_array_value($options, "verification_status");
        if ($verification_status) {
            $where .= " AND $assets_table.verification_status='$verification_status'";
        }

        $worksheet_source = get_array_value($options, "worksheet_source");
        if ($worksheet_source) {
            $where .= " AND $assets_table.worksheet_source='$worksheet_source'";
        }

        $sql = "SELECT $assets_table.*
                FROM $assets_table
                WHERE $assets_table.deleted=0 $where
                ORDER BY $assets_table.id DESC";

        return $this->db->query($sql);
    }

    function get_category_summary() {
        $assets_table = $this->table;
        $sql = "SELECT category_segment3, COUNT(*) as asset_count, SUM(fb_cost) as total_fb_cost, SUM(adjusted_cost) as total_adjusted_cost, SUM(net_book_value) as total_nbv
                FROM $assets_table
                WHERE deleted=0
                GROUP BY category_segment3
                ORDER BY total_adjusted_cost DESC";
        return $this->db->query($sql);
    }

    function get_portfolio_totals() {
        $assets_table = $this->table;
        $sql = "SELECT 
                    COUNT(*) as total_assets,
                    COALESCE(SUM(asset_units), 0) as total_units,
                    COUNT(DISTINCT category_segment3) as category_count,
                    COALESCE(SUM(fb_cost), 0) as total_fb_cost,
                    COALESCE(SUM(adjusted_cost), 0) as total_adjusted_cost,
                    COALESCE(SUM(accumulated_depreciation), 0) as total_depreciation,
                    COALESCE(SUM(net_book_value), 0) as total_nbv,
                    COUNT(CASE WHEN verification_status='VERIFIED' THEN 1 END) as verified_count,
                    COUNT(CASE WHEN verification_status='UNVERIFIED' THEN 1 END) as unverified_count,
                    COALESCE(SUM(adjusted_cost - accumulated_depreciation - net_book_value), 0) as balance_difference,
                    COUNT(*) FILTER (WHERE adjusted_cost IS NULL OR accumulated_depreciation IS NULL OR net_book_value IS NULL OR ABS(adjusted_cost - accumulated_depreciation - net_book_value) > 0.01) as balance_exceptions
                FROM $assets_table
                WHERE deleted=0";
        return $this->db->query($sql)->getRow();
    }
}
