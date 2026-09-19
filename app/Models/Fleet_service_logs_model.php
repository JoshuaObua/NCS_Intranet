<?php

namespace App\Models;

class Fleet_service_logs_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'fleet_service_logs';
        parent::__construct($this->table);
        parent::init_activity_log("fleet_service", "service_type", "fleet_vehicle", "vehicle_id");
    }

    function get_details($options = array()) {
        $logs_table     = $this->db->prefixTable('fleet_service_logs');
        $vehicles_table = $this->db->prefixTable('fleet_vehicles');
        $users_table    = $this->db->prefixTable('users');

        $where      = "";
        $id         = $this->_get_clean_value($options, "id");
        $vehicle_id = $this->_get_clean_value($options, "vehicle_id");
        $search     = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $logs_table.id=$id";
        }
        if ($vehicle_id) {
            $where .= " AND $logs_table.vehicle_id=$vehicle_id";
        }
        if ($search) {
            $where .= " AND ($logs_table.service_type ILIKE '%$search%'
                OR $logs_table.service_provider ILIKE '%$search%'
                OR $logs_table.description ILIKE '%$search%')";
        }

        $sql = "SELECT $logs_table.*,
                CONCAT($vehicles_table.make,' ',$vehicles_table.model,' (',$vehicles_table.plate_number,')') AS vehicle_label,
                $vehicles_table.plate_number,
                CONCAT(creator.first_name,' ',creator.last_name) AS created_by_user,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $logs_table
            LEFT JOIN $vehicles_table ON $vehicles_table.id=$logs_table.vehicle_id AND $vehicles_table.deleted=0
            LEFT JOIN $users_table AS creator ON creator.id=$logs_table.created_by
            WHERE $logs_table.deleted=0 $where
            ORDER BY $logs_table.service_date DESC";

        return $this->db->query($sql);
    }

    function get_service_summary($vehicle_id) {
        $table = $this->db->prefixTable('fleet_service_logs');
        $sql = "SELECT COUNT(id) AS total_services, COALESCE(SUM(cost),0) AS total_cost
                FROM $table WHERE deleted=0 AND vehicle_id=$vehicle_id";
        return $this->db->query($sql)->getRow();
    }
}
