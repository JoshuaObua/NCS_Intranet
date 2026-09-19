<?php

namespace App\Models;

class Fleet_routes_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'fleet_routes';
        parent::__construct($this->table);
        parent::init_activity_log("fleet_route", "title", "fleet_vehicle", "vehicle_id");
    }

    function get_details($options = array()) {
        $routes_table   = $this->db->prefixTable('fleet_routes');
        $vehicles_table = $this->db->prefixTable('fleet_vehicles');
        $users_table    = $this->db->prefixTable('users');

        $where      = "";
        $id         = $this->_get_clean_value($options, "id");
        $vehicle_id = $this->_get_clean_value($options, "vehicle_id");
        $status     = $this->_get_clean_value($options, "status");
        $search     = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $routes_table.id=$id";
        }
        if ($vehicle_id) {
            $where .= " AND $routes_table.vehicle_id=$vehicle_id";
        }
        if ($status) {
            $where .= " AND $routes_table.status='$status'";
        }
        if ($search) {
            $where .= " AND ($routes_table.title ILIKE '%$search%'
                OR $routes_table.start_location ILIKE '%$search%'
                OR $routes_table.end_location ILIKE '%$search%')";
        }

        $sql = "SELECT $routes_table.*,
                CONCAT($vehicles_table.make,' ',$vehicles_table.model,' (',$vehicles_table.plate_number,')') AS vehicle_label,
                CONCAT(driver.first_name,' ',driver.last_name) AS driver_name,
                CONCAT(creator.first_name,' ',creator.last_name) AS created_by_user,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $routes_table
            LEFT JOIN $vehicles_table ON $vehicles_table.id=$routes_table.vehicle_id
            LEFT JOIN $users_table AS driver ON driver.id=$routes_table.assigned_driver
            LEFT JOIN $users_table AS creator ON creator.id=$routes_table.created_by
            WHERE $routes_table.deleted=0 $where
            ORDER BY $routes_table.scheduled_date DESC NULLS LAST, $routes_table.id DESC";

        return $this->db->query($sql);
    }
}
