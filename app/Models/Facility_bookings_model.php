<?php

namespace App\Models;

class Facility_bookings_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'facility_bookings';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $bookings_table = $this->table;
        $facilities_table = $this->db->prefixTable('facilities');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $bookings_table.id=$id";
        }

        $facility_id = get_array_value($options, "facility_id");
        if ($facility_id) {
            $where .= " AND $bookings_table.facility_id=$facility_id";
        }

        $payment_status = get_array_value($options, "payment_status");
        if ($payment_status) {
            $where .= " AND $bookings_table.payment_status='$payment_status'";
        }

        $sql = "SELECT $bookings_table.*, $facilities_table.title AS facility_name, $facilities_table.location AS facility_location
                FROM $bookings_table
                LEFT JOIN $facilities_table ON $facilities_table.id = $bookings_table.facility_id
                WHERE $bookings_table.deleted=0 $where
                ORDER BY $bookings_table.id DESC";

        return $this->db->query($sql);
    }
}
