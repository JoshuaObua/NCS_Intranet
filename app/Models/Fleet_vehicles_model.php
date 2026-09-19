<?php

namespace App\Models;

class Fleet_vehicles_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'fleet_vehicles';
        parent::__construct($this->table);
        parent::init_activity_log("fleet_vehicle", "plate_number", "", "");
    }

    function get_details($options = array()) {
        $vehicles_table = $this->db->prefixTable('fleet_vehicles');
        $users_table    = $this->db->prefixTable('users');

        $where  = "";
        $id     = $this->_get_clean_value($options, "id");
        $status = $this->_get_clean_value($options, "status");
        $search = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $vehicles_table.id=$id";
        }
        if ($status) {
            $where .= " AND $vehicles_table.status='$status'";
        }
        if ($search) {
            $where .= " AND ($vehicles_table.vin ILIKE '%$search%'
                OR $vehicles_table.plate_number ILIKE '%$search%'
                OR $vehicles_table.make ILIKE '%$search%'
                OR $vehicles_table.model ILIKE '%$search%')";
        }

        $sql = "SELECT $vehicles_table.*,
                CONCAT(driver.first_name,' ',driver.last_name) AS driver_name,
                driver.image AS driver_avatar,
                CONCAT(creator.first_name,' ',creator.last_name) AS created_by_user,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $vehicles_table
            LEFT JOIN $users_table AS driver ON driver.id=$vehicles_table.assigned_driver
            LEFT JOIN $users_table AS creator ON creator.id=$vehicles_table.created_by
            WHERE $vehicles_table.deleted=0 $where
            ORDER BY $vehicles_table.id DESC";

        return $this->db->query($sql);
    }

    function get_vehicle_dropdown() {
        $table = $this->db->prefixTable('fleet_vehicles');
        $sql = "SELECT id, CONCAT(make,' ',model,' (',plate_number,')') AS title
                FROM $table WHERE deleted=0 ORDER BY make, model";
        return $this->db->query($sql)->getResult();
    }

    function count_by_status() {
        $table = $this->db->prefixTable('fleet_vehicles');
        $sql = "SELECT status, COUNT(id) AS total FROM $table WHERE deleted=0 GROUP BY status";
        return $this->db->query($sql)->getResult();
    }

    function get_service_due($days_ahead = 30) {
        $table = $this->db->prefixTable('fleet_vehicles');
        $date  = date('Y-m-d', strtotime("+$days_ahead days"));
        $sql   = "SELECT * FROM $table
                  WHERE deleted=0 AND next_service_date IS NOT NULL AND next_service_date <= '$date'
                  ORDER BY next_service_date ASC";
        return $this->db->query($sql)->getResult();
    }
}
