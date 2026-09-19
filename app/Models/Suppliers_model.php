<?php

namespace App\Models;

class Suppliers_model extends Crud_model {

    protected $table = 'suppliers';

    function __construct() {
        $this->table = 'suppliers';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $suppliers_table = $this->db->prefixTable('suppliers');
        $contacts_table = $this->db->prefixTable('supplier_contacts');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $suppliers_table.id=$id";
        }

        $category = get_array_value($options, "category");
        if ($category) {
            $where .= " AND $suppliers_table.category=" . $this->db->escape($category);
        }

        $prequalification_status = get_array_value($options, "prequalification_status");
        if ($prequalification_status) {
            $where .= " AND $suppliers_table.prequalification_status=" . $this->db->escape($prequalification_status);
        }

        $is_blacklisted = get_array_value($options, "is_blacklisted");
        if ($is_blacklisted !== null && $is_blacklisted !== "") {
            $where .= " AND $suppliers_table.is_blacklisted=" . ($is_blacklisted ? "true" : "false");
        }

        $sql = "SELECT $suppliers_table.*, 
                    (SELECT COUNT(id) FROM $contacts_table WHERE supplier_id=$suppliers_table.id AND deleted=0) AS total_contacts,
                    (SELECT CONCAT(first_name, ' ', last_name, ' (', job_title, ')') FROM $contacts_table WHERE supplier_id=$suppliers_table.id AND is_primary_contact=true AND deleted=0 LIMIT 1) AS primary_contact_name,
                    (SELECT email FROM $contacts_table WHERE supplier_id=$suppliers_table.id AND is_primary_contact=true AND deleted=0 LIMIT 1) AS primary_contact_email,
                    (SELECT phone FROM $contacts_table WHERE supplier_id=$suppliers_table.id AND is_primary_contact=true AND deleted=0 LIMIT 1) AS primary_contact_phone
                FROM $suppliers_table
                WHERE $suppliers_table.deleted=0 $where
                ORDER BY $suppliers_table.id DESC";

        return $this->db->query($sql);
    }

    function get_supplier_stats() {
        $suppliers_table = $this->db->prefixTable('suppliers');
        $contacts_table = $this->db->prefixTable('supplier_contacts');

        $sql = "SELECT 
                    COUNT($suppliers_table.id) AS total_suppliers,
                    SUM(CASE WHEN $suppliers_table.prequalification_status = 'PRE_QUALIFIED' AND $suppliers_table.is_blacklisted = false THEN 1 ELSE 0 END) AS prequalified_count,
                    SUM(CASE WHEN $suppliers_table.is_blacklisted = true THEN 1 ELSE 0 END) AS blacklisted_count,
                    SUM(CASE WHEN $suppliers_table.prequalification_status = 'PROVISIONAL' THEN 1 ELSE 0 END) AS provisional_count,
                    (SELECT COUNT(id) FROM $contacts_table WHERE deleted=0) AS total_contacts,
                    AVG($suppliers_table.rating) AS avg_rating
                FROM $suppliers_table
                WHERE $suppliers_table.deleted=0";

        return $this->db->query($sql)->getRow();
    }
}
