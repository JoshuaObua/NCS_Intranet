<?php
namespace App\Controllers;

class Hr_appraisals extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("hr_appraisals");
    }

    private function _check_access() {
        if (!get_setting("module_hr")) { app_redirect("forbidden"); }
        $this->access_only_team_members();
        $perm = get_array_value($this->login_user->permissions, "hr_appraisals");
        if (!$this->login_user->is_admin && !$perm) {
            app_redirect("forbidden");
        }
    }

    private function _require_manage() {
        $this->_check_access();
        $perm = get_array_value($this->login_user->permissions, "hr_appraisals");
        if (!$this->login_user->is_admin && $perm === "read_only") {
            app_redirect("forbidden");
        }
    }

    function index() {
        $this->_check_access();
        $view_data = [];
        $year = date('Y');
        $view_data['years'] = [];
        for ($y = $year; $y >= $year - 5; $y--) {
            $view_data['years'][$y] = $y;
        }
        $view_data['status_dropdown'] = [
            "" => "-- " . app_lang("all") . " --",
            "draft"          => app_lang("hr_draft"),
            "self_submitted" => app_lang("hr_self_submitted"),
            "supervisor_reviewed" => app_lang("hr_supervisor_reviewed"),
            "completed"      => app_lang("hr_completed"),
        ];
        return $this->template->rander("hr/appraisals/index", $view_data);
    }

    function list_data() {
        $this->_check_access();
        $options = [
            "search"      => $this->request->getPost("search"),
            "status"      => $this->request->getPost("status"),
            "period_year" => $this->request->getPost("period_year"),
        ];
        // Non-admin/non-hr sees only own appraisals
        $perm = get_array_value($this->login_user->permissions, "hr_appraisals");
        if (!$this->login_user->is_admin && !$perm) {
            $options["employee_id"] = $this->login_user->id;
        }
        $result = $this->Hr_appraisals_model->get_details($options)->getResult();
        $list_data = [];
        foreach ($result as $row) {
            $list_data[] = $this->_make_row($row);
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(["data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found]);
    }

    private function _make_row($row) {
        $can_manage = $this->login_user->is_admin ||
            (get_array_value($this->login_user->permissions, "hr_appraisals") && get_array_value($this->login_user->permissions, "hr_appraisals") !== "read_only");

        $status_badges = [
            "draft"               => "secondary",
            "self_submitted"      => "info",
            "supervisor_reviewed" => "warning",
            "completed"           => "success",
        ];
        $badge = $status_badges[$row->status] ?? "secondary";
        $status_label = "<span class='badge bg-{$badge}'>" . app_lang("hr_" . $row->status) . "</span>";

        $actions = "<a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-default btn-appraisal-view'><i class='fa fa-eye'></i></a> ";
        if ($can_manage) {
            $actions .= "<a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-default btn-appraisal-edit'><i class='fa fa-pencil'></i></a> ";
            $actions .= "<a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-danger btn-appraisal-delete ajax-btn-delete'><i class='fa fa-times'></i></a>";
        }

        return [
            get_avatar($row->employee_image) . " " . $row->employee_name,
            $row->employee_job_title,
            $row->period_name,
            $row->period_year,
            $row->supervisor_name,
            $status_label,
            $actions,
        ];
    }

    function modal_form() {
        $this->_require_manage();
        $view_data = ["model_info" => $this->Hr_appraisals_model->get_one($this->request->getPost("id"))];
        $view_data["users_dropdown"] = $this->_get_users_dropdown();
        return $this->template->view("hr/appraisals/modal_form", $view_data);
    }

    function save() {
        $this->_require_manage();
        $id = $this->request->getPost("id");

        $data = [
            "employee_id"   => $this->request->getPost("employee_id"),
            "supervisor_id" => $this->request->getPost("supervisor_id"),
            "period_name"   => $this->request->getPost("period_name"),
            "period_year"   => (int)$this->request->getPost("period_year"),
            "status"        => $this->request->getPost("status") ?: "draft",
        ];

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        } else {
            $data["updated_at"] = get_current_utc_time();
        }

        $save_id = $this->Hr_appraisals_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(["success" => true, "id" => $save_id, "message" => app_lang("record_saved")]);
        } else {
            echo json_encode(["success" => false, "message" => app_lang("error_occurred")]);
        }
    }

    function delete() {
        $this->_require_manage();
        $id = $this->request->getPost("id");
        $this->Hr_appraisals_model->delete($id);
        echo json_encode(["success" => true, "message" => app_lang("record_deleted")]);
    }

    function view($id) {
        $this->_check_access();
        $appraisal = $this->Hr_appraisals_model->get_one($id);
        if (!$appraisal->id) { app_redirect("forbidden"); }

        // Self or supervisor or hr-perm or admin
        $perm = get_array_value($this->login_user->permissions, "hr_appraisals");
        $is_own = ($appraisal->employee_id == $this->login_user->id);
        $is_supervisor = ($appraisal->supervisor_id == $this->login_user->id);
        if (!$this->login_user->is_admin && !$perm && !$is_own && !$is_supervisor) {
            app_redirect("forbidden");
        }

        $view_data = ["appraisal" => $appraisal];
        $view_data["items"] = $this->Hr_appraisal_items_model->get_items($id);
        $view_data["is_own"] = $is_own;
        $view_data["is_supervisor"] = $is_supervisor;
        $view_data["can_manage"] = $this->login_user->is_admin || ($perm && $perm !== "read_only");
        return $this->template->rander("hr/appraisals/view", $view_data);
    }

    function save_items() {
        $id = $this->request->getPost("appraisal_id");
        $appraisal = $this->Hr_appraisals_model->get_one($id);
        if (!$appraisal->id) { app_redirect("forbidden"); }

        $is_own = ($appraisal->employee_id == $this->login_user->id);
        $is_supervisor = ($appraisal->supervisor_id == $this->login_user->id);
        $perm = get_array_value($this->login_user->permissions, "hr_appraisals");
        if (!$this->login_user->is_admin && !$perm && !$is_own && !$is_supervisor) {
            echo json_encode(["success" => false, "message" => app_lang("access_denied")]);
            return;
        }

        $items = $this->request->getPost("items");
        if (is_array($items)) {
            foreach ($items as $item) {
                $item_data = [
                    "appraisal_id" => $id,
                    "section"      => $item["section"] ?? "",
                    "kpi"          => $item["kpi"] ?? "",
                    "target"       => $item["target"] ?? "",
                    "self_score"   => $item["self_score"] ?? null,
                    "supervisor_score" => $item["supervisor_score"] ?? null,
                    "comments"     => $item["comments"] ?? "",
                ];
                $item_id = $item["id"] ?? null;
                $this->Hr_appraisal_items_model->ci_save($item_data, $item_id ?: null);
            }
        }

        echo json_encode(["success" => true, "message" => app_lang("record_saved")]);
    }

    function submit_self() {
        $id = $this->request->getPost("id");
        $appraisal = $this->Hr_appraisals_model->get_one($id);
        if (!$appraisal->id || $appraisal->employee_id != $this->login_user->id) {
            echo json_encode(["success" => false, "message" => app_lang("access_denied")]);
            return;
        }
        $this->Hr_appraisals_model->ci_save(["status" => "self_submitted", "self_submitted_at" => get_current_utc_time()], $id);
        echo json_encode(["success" => true, "message" => app_lang("record_saved")]);
    }

    function supervisor_review() {
        $id = $this->request->getPost("id");
        $appraisal = $this->Hr_appraisals_model->get_one($id);
        if (!$appraisal->id || $appraisal->supervisor_id != $this->login_user->id) {
            echo json_encode(["success" => false, "message" => app_lang("access_denied")]);
            return;
        }
        $this->Hr_appraisals_model->ci_save([
            "status"              => "supervisor_reviewed",
            "supervisor_reviewed_at" => get_current_utc_time(),
            "supervisor_reviewed_by" => $this->login_user->id,
        ], $id);
        echo json_encode(["success" => true, "message" => app_lang("record_saved")]);
    }

    function complete() {
        $this->_require_manage();
        $id = $this->request->getPost("id");
        $this->Hr_appraisals_model->ci_save(["status" => "completed", "completed_at" => get_current_utc_time()], $id);
        echo json_encode(["success" => true, "message" => app_lang("record_saved")]);
    }

    private function _get_users_dropdown() {
        $users = $this->Users_model->get_active_team_members_for_dropdown();
        $dropdown = ["" => "-- " . app_lang("select_a_member") . " --"];
        foreach ($users as $u) {
            $dropdown[$u->id] = $u->first_name . " " . $u->last_name;
        }
        return $dropdown;
    }
}
