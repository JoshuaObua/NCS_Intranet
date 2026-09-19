<?php

namespace App\Models;

class Hr_memo_recipients_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'hr_memo_recipients';
        parent::__construct($this->table);
    }

    function get_recipients($memo_id) {
        $recipients_table = $this->db->prefixTable('hr_memo_recipients');
        $users_table      = $this->db->prefixTable('users');

        $sql = "SELECT $recipients_table.*,
                CONCAT($users_table.first_name,' ',$users_table.last_name) AS recipient_name,
                $users_table.job_title AS recipient_title,
                $users_table.image AS recipient_image
            FROM $recipients_table
            LEFT JOIN $users_table ON $users_table.id=$recipients_table.recipient_id
            WHERE $recipients_table.memo_id=$memo_id AND $recipients_table.deleted=0
            ORDER BY $users_table.first_name ASC";

        return $this->db->query($sql)->getResult();
    }

    function mark_read($memo_id, $user_id) {
        $table = $this->db->prefixTable('hr_memo_recipients');
        return $this->db->query(
            "UPDATE $table SET read_at=NOW() WHERE memo_id=$memo_id AND recipient_id=$user_id AND read_at IS NULL"
        );
    }

    function save_response($recipient_record_id, $response) {
        $table = $this->db->prefixTable('hr_memo_recipients');
        $response = $this->db->escapeStr($response);
        return $this->db->query(
            "UPDATE $table SET response='$response', responded_at=NOW() WHERE id=$recipient_record_id"
        );
    }

    function delete_by_memo($memo_id) {
        $table = $this->db->prefixTable('hr_memo_recipients');
        return $this->db->query("UPDATE $table SET deleted=1 WHERE memo_id=$memo_id");
    }

    function add_recipients($memo_id, array $user_ids) {
        foreach ($user_ids as $uid) {
            $uid = (int)$uid;
            if (!$uid) continue;
            $exists = $this->get_one_where(array('memo_id' => $memo_id, 'recipient_id' => $uid, 'deleted' => 0));
            if (!$exists->id) {
                $this->ci_save(array(
                    'memo_id'      => $memo_id,
                    'recipient_id' => $uid,
                    'created_at'   => get_current_utc_time(),
                ));
            }
        }
    }
}
