<?php

namespace App\Models;

class Facility_inspections_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'facility_inspections';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $inspections_table = $this->table;
        $bookings_table = $this->db->prefixTable('facility_bookings');
        $facilities_table = $this->db->prefixTable('facilities');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $inspections_table.id=$id";
        }

        $sql = "SELECT $inspections_table.*, $bookings_table.booking_reference, $bookings_table.event_title, $bookings_table.client_name, $facilities_table.title AS facility_name
                FROM $inspections_table
                LEFT JOIN $bookings_table ON $bookings_table.id = $inspections_table.booking_id
                LEFT JOIN $facilities_table ON $facilities_table.id = $bookings_table.facility_id
                WHERE $inspections_table.deleted=0 $where
                ORDER BY $inspections_table.id DESC";

        return $this->db->query($sql);
    }
}
