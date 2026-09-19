<?php
namespace App\Controllers;

class Hr_memos extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("hr_memos");
    }

    private function _check_access() {
        if (!get_setting("module_hr")) { app_redirect("forbidden"); }
        $this->access_only_team_members();
    }

    private function _require_manage() {
        $this->_check_access();
        $perm = get_array_value($this->login_user->permissions, "hr_memos");
        if (!$this->login_user->is_admin && $perm !== "all") {
            app_redirect("forbidden");
        }
    }

    // Outbox: memos I sent (admin/hr_memos manage)
    function index() {
        $this->_require_manage();
        return $this->template->rander("hr/memos/index", []);
    }

    // Inbox: memos I received (all team members)
    function inbox() {
        $this->_check_access();
        $memos  = $this->Hr_memos_model->get_inbox($this->login_user->id);
        $unread = $this->Hr_memos_model->count_unread($this->login_user->id);
        return $this->template->rander("hr/memos/inbox", ["memos" => $memos, "unread" => $unread]);
    }

    function list_data() {
        $this->_require_manage();
        $options = [
            "search"      => $this->request->getPost("search"),
            "priority"    => $this->request->getPost("priority"),
            "target_type" => $this->request->getPost("target_type"),
            "sender_id"   => $this->login_user->is_admin ? null : $this->login_user->id,
        ];
        $result = $this->Hr_memos_model->get_details($options)->getResult();
        $list_data = [];
        foreach ($result as $row) {
            $list_data[] = $this->_make_row($row);
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(["data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found]);
    }

    private function _make_row($row) {
        $priority_badges = ["low" => "success", "normal" => "info", "high" => "warning", "urgent" => "danger"];
        $badge = $priority_badges[$row->priority] ?? "secondary";
        $priority_label = "<span class='badge bg-{$badge}'>" . app_lang("hr_" . $row->priority) . "</span>";

        $actions = "<a href='" . site_url("hr_memos/view/{$row->id}") . "' class='btn btn-xs btn-default'><i class='fa fa-eye'></i></a> ";
        $actions .= "<a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-default btn-memo-edit'><i class='fa fa-pencil'></i></a> ";
        $actions .= "<a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-danger btn-memo-delete ajax-btn-delete'><i class='fa fa-times'></i></a>";

        return [
            $row->memo_number,
            $row->subject,
            $row->sender_name,
            format_to_date($row->created_at),
            app_lang("hr_target_" . $row->target_type),
            $priority_label,
            $actions,
        ];
    }

    function modal_form() {
        $this->_require_manage();
        $id = $this->request->getPost("id");
        $memo = $this->Hr_memos_model->get_one($id);
        $view_data = ["model_info" => $memo];
        $view_data["priority_dropdown"] = [
            "low" => app_lang("hr_low"), "normal" => app_lang("hr_normal"),
            "high" => app_lang("hr_high"), "urgent" => app_lang("hr_urgent"),
        ];
        $view_data["target_dropdown"] = [
            "all"        => app_lang("hr_all_staff"),
            "department" => app_lang("hr_department"),
            "individual" => app_lang("hr_individual"),
        ];
        $view_data["departments"] = $this->Hr_profiles_model->get_departments();
        $view_data["users_dropdown"] = $this->_get_users_dropdown();
        if ($id) {
            $view_data["recipients"] = $this->Hr_memo_recipients_model->get_recipients($id);
        }
        return $this->template->view("hr/memos/modal_form", $view_data);
    }

    function save() {
        $this->_require_manage();
        $id = $this->request->getPost("id");

        $data = [
            "subject"     => $this->request->getPost("subject"),
            "body"        => $this->request->getPost("body"),
            "priority"    => $this->request->getPost("priority") ?: "normal",
            "target_type" => $this->request->getPost("target_type") ?: "all",
            "department"  => $this->request->getPost("department"),
        ];

        if (!$id) {
            $data["memo_number"] = $this->Hr_memos_model->generate_memo_number();
            $data["sender_id"]   = $this->login_user->id;
            $data["created_at"]  = get_current_utc_time();
        } else {
            $data["updated_at"]  = get_current_utc_time();
        }

        $save_id = $this->Hr_memos_model->ci_save($data, $id);
        if (!$save_id) {
            echo json_encode(["success" => false, "message" => app_lang("error_occurred")]);
            return;
        }

        // Handle recipients
        $target_type = $data["target_type"];
        if ($target_type === "all") {
            // Handled on view: all active team members are implicitly recipients
        } elseif ($target_type === "department") {
            // Resolve users in department and add
            $dept = $data["department"];
            if ($dept) {
                $dept_users = $this->Hr_profiles_model->get_by_department($dept);
                $uids = array_map(fn($u) => $u->user_id, $dept_users);
                $this->Hr_memo_recipients_model->add_recipients($save_id, $uids);
            }
        } elseif ($target_type === "individual") {
            $user_ids = $this->request->getPost("recipient_ids");
            if (is_array($user_ids)) {
                $this->Hr_memo_recipients_model->add_recipients($save_id, $user_ids);
            }
        }

        echo json_encode(["success" => true, "id" => $save_id, "message" => app_lang("record_saved")]);
    }

    function delete() {
        $this->_require_manage();
        $id = $this->request->getPost("id");
        $this->Hr_memos_model->delete($id);
        echo json_encode(["success" => true, "message" => app_lang("record_deleted")]);
    }

    function view($id) {
        $this->_check_access();
        $memo = $this->Hr_memos_model->get_one($id);
        if (!$memo->id) { app_redirect("forbidden"); }

        $perm = get_array_value($this->login_user->permissions, "hr_memos");
        $can_manage = $this->login_user->is_admin || $perm === "all";

        // Only sender or admin/hr_memos can view outbox memo directly; recipients see via inbox
        $recipient_record = null;
        $recipients = $this->Hr_memo_recipients_model->get_recipients($id);
        foreach ($recipients as $r) {
            if ($r->recipient_id == $this->login_user->id) {
                $recipient_record = $r;
                break;
            }
        }

        if (!$can_manage && !$recipient_record && $memo->sender_id != $this->login_user->id) {
            app_redirect("forbidden");
        }

        // Mark read if recipient hasn't read yet
        if ($recipient_record && !$recipient_record->read_at) {
            $this->Hr_memo_recipients_model->mark_read($id, $this->login_user->id);
        }

        $view_data = [
            "memo"             => $memo,
            "recipients"       => $recipients,
            "recipient_record" => $recipient_record,
            "can_manage"       => $can_manage,
        ];
        return $this->template->rander("hr/memos/view", $view_data);
    }

    function save_response() {
        $this->_check_access();
        $recipient_record_id = $this->request->getPost("recipient_record_id");
        $response = $this->request->getPost("response");
        $this->Hr_memo_recipients_model->save_response($recipient_record_id, $response);
        echo json_encode(["success" => true, "message" => app_lang("record_saved")]);
    }

    // AJAX: unread count badge (called from sidebar)
    function unread_count() {
        $this->_check_access();
        echo json_encode(["count" => $this->Hr_memos_model->count_unread($this->login_user->id)]);
    }

    private function _get_users_dropdown() {
        $users = $this->Users_model->get_active_team_members_for_dropdown();
        $dropdown = [];
        foreach ($users as $u) {
            $dropdown[$u->id] = $u->first_name . " " . $u->last_name;
        }
        return $dropdown;
    }
}
