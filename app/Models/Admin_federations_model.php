<?php

namespace App\Models;

class Admin_federations_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'admin_federations';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $federations_table = $this->table;

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $federations_table.id=$id";
        }

        $sport_category = get_array_value($options, "sport_category");
        if ($sport_category) {
            $where .= " AND $federations_table.sport_category='$sport_category'";
        }

        $governance_status = get_array_value($options, "governance_status");
        if ($governance_status) {
            $where .= " AND $federations_table.governance_status='$governance_status'";
        }

        $sql = "SELECT $federations_table.*
                FROM $federations_table
                WHERE $federations_table.deleted=0 $where
                ORDER BY $federations_table.id DESC";

        return $this->db->query($sql);
    }
}
