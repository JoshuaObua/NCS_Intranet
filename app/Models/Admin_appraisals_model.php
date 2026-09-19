<?php

namespace App\Models;

class Admin_appraisals_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'admin_appraisals';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $appraisals_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $appraisals_table.id=$id";
        }

        $asset_class = get_array_value($options, "asset_class");
        if ($asset_class) {
            $where .= " AND $appraisals_table.asset_class='$asset_class'";
        }

        $condition_rating = get_array_value($options, "condition_rating");
        if ($condition_rating) {
            $where .= " AND $appraisals_table.condition_rating='$condition_rating'";
        }

        $land_title_status = get_array_value($options, "land_title_status");
        if ($land_title_status) {
            $where .= " AND $appraisals_table.land_title_status='$land_title_status'";
        }

        $sql = "SELECT $appraisals_table.*
                FROM $appraisals_table
                WHERE $appraisals_table.deleted=0 $where
                ORDER BY $appraisals_table.id DESC";

        return $this->db->query($sql);
    }
}
