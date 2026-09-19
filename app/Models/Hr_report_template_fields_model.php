<?php

namespace App\Models;

class Hr_report_template_fields_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'hr_report_template_fields';
        parent::__construct($this->table);
    }

    function get_fields($template_id) {
        $table = $this->db->prefixTable('hr_report_template_fields');
        $sql = "SELECT * FROM $table WHERE template_id=$template_id AND deleted=0 ORDER BY sort_order ASC, id ASC";
        return $this->db->query($sql)->getResult();
    }

    function delete_by_template($template_id) {
        $table = $this->db->prefixTable('hr_report_template_fields');
        return $this->db->query("UPDATE $table SET deleted=1 WHERE template_id=$template_id");
    }
}
