<?php

namespace App\Models;

class Supplier_contacts_model extends Crud_model {

    protected $table = 'supplier_contacts';

    function __construct() {
        $this->table = 'supplier_contacts';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $contacts_table = $this->db->prefixTable('supplier_contacts');
        $suppliers_table = $this->db->prefixTable('suppliers');

        $where = "";
        $id = get_array_value($options, "id");
        if ($id) {
            $where .= " AND $contacts_table.id=$id";
        }

        $supplier_id = get_array_value($options, "supplier_id");
        if ($supplier_id) {
            $where .= " AND $contacts_table.supplier_id=$supplier_id";
        }

        $is_primary_contact = get_array_value($options, "is_primary_contact");
        if ($is_primary_contact !== null && $is_primary_contact !== "") {
            $where .= " AND $contacts_table.is_primary_contact=" . ($is_primary_contact ? "true" : "false");
        }

        $sql = "SELECT $contacts_table.*, 
                    $suppliers_table.company_name AS supplier_company_name,
                    $suppliers_table.supplier_code AS supplier_code,
                    $suppliers_table.category AS supplier_category,
                    $suppliers_table.prequalification_status AS supplier_status
                FROM $contacts_table
                LEFT JOIN $suppliers_table ON $suppliers_table.id = $contacts_table.supplier_id
                WHERE $contacts_table.deleted=0 $where
                ORDER BY $contacts_table.is_primary_contact DESC, $contacts_table.id ASC";

        return $this->db->query($sql);
    }
}
