<?php

namespace App\Models;

class Hr_memos_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'hr_memos';
        parent::__construct($this->table);
        parent::init_activity_log("hr_memo", "subject", "", "");
    }

    function get_details($options = array()) {
        $memos_table = $this->db->prefixTable('hr_memos');
        $users_table = $this->db->prefixTable('users');

        $where       = "";
        $id          = $this->_get_clean_value($options, "id");
        $sender_id   = $this->_get_clean_value($options, "sender_id");
        $priority    = $this->_get_clean_value($options, "priority");
        $target_type = $this->_get_clean_value($options, "target_type");
        $search      = $this->_get_clean_value($options, "search");

        if ($id) {
            $where .= " AND $memos_table.id=$id";
        }
        if ($sender_id) {
            $where .= " AND $memos_table.sender_id=$sender_id";
        }
        if ($priority) {
            $where .= " AND $memos_table.priority='$priority'";
        }
        if ($target_type) {
            $where .= " AND $memos_table.target_type='$target_type'";
        }
        if ($search) {
            $where .= " AND ($memos_table.subject ILIKE '%$search%'
                OR $memos_table.memo_number ILIKE '%$search%'
                OR $users_table.first_name ILIKE '%$search%'
                OR $users_table.last_name ILIKE '%$search%')";
        }

        $sql = "SELECT $memos_table.*,
                CONCAT($users_table.first_name,' ',$users_table.last_name) AS sender_name,
                $users_table.job_title AS sender_title,
                $users_table.image AS sender_image,
                COUNT(*) OVER() AS _pg_found_rows
            FROM $memos_table
            LEFT JOIN $users_table ON $users_table.id=$memos_table.sender_id
            WHERE $memos_table.deleted=0 $where
            ORDER BY $memos_table.created_at DESC";

        return $this->db->query($sql);
    }

    function generate_memo_number() {
        $table = $this->db->prefixTable('hr_memos');
        $year  = date('Y');
        $sql   = "SELECT COUNT(id)+1 AS seq FROM $table WHERE EXTRACT(YEAR FROM created_at)=$year";
        $row   = $this->db->query($sql)->getRow();
        $seq   = $row ? str_pad($row->seq, 4, '0', STR_PAD_LEFT) : '0001';
        return "NCS/MEMO/{$year}/{$seq}";
    }

    function get_inbox($user_id) {
        $memos_table      = $this->db->prefixTable('hr_memos');
        $recipients_table = $this->db->prefixTable('hr_memo_recipients');
        $users_table      = $this->db->prefixTable('users');

        $sql = "SELECT $memos_table.*,
                CONCAT($users_table.first_name,' ',$users_table.last_name) AS sender_name,
                $users_table.job_title AS sender_title,
                $recipients_table.read_at,
                $recipients_table.response,
                $recipients_table.id AS recipient_record_id
            FROM $recipients_table
            INNER JOIN $memos_table ON $memos_table.id=$recipients_table.memo_id AND $memos_table.deleted=0
            LEFT JOIN $users_table ON $users_table.id=$memos_table.sender_id
            WHERE $recipients_table.deleted=0 AND $recipients_table.recipient_id=$user_id
            ORDER BY $memos_table.created_at DESC";

        return $this->db->query($sql)->getResult();
    }

    function count_unread($user_id) {
        $memos_table      = $this->db->prefixTable('hr_memos');
        $recipients_table = $this->db->prefixTable('hr_memo_recipients');

        $sql = "SELECT COUNT($recipients_table.id) AS total
            FROM $recipients_table
            INNER JOIN $memos_table ON $memos_table.id=$recipients_table.memo_id AND $memos_table.deleted=0
            WHERE $recipients_table.deleted=0 AND $recipients_table.recipient_id=$user_id AND $recipients_table.read_at IS NULL";

        $row = $this->db->query($sql)->getRow();
        return $row ? (int)$row->total : 0;
    }
}
