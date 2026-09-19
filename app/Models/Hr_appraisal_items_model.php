<?php

namespace App\Models;

class Hr_appraisal_items_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'hr_appraisal_items';
        parent::__construct($this->table);
    }

    function get_items($appraisal_id) {
        $table = $this->db->prefixTable('hr_appraisal_items');
        $sql = "SELECT * FROM $table WHERE appraisal_id=$appraisal_id AND deleted=0 ORDER BY section, id ASC";
        return $this->db->query($sql)->getResult();
    }

    function get_items_by_section($appraisal_id, $section) {
        $table = $this->db->prefixTable('hr_appraisal_items');
        $sql = "SELECT * FROM $table WHERE appraisal_id=$appraisal_id AND section='$section' AND deleted=0 ORDER BY id ASC";
        return $this->db->query($sql)->getResult();
    }

    function delete_by_appraisal($appraisal_id) {
        $table = $this->db->prefixTable('hr_appraisal_items');
        return $this->db->query("UPDATE $table SET deleted=1 WHERE appraisal_id=$appraisal_id");
    }
}
