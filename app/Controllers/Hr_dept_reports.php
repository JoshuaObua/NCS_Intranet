<?php
namespace App\Controllers;

class Hr_dept_reports extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("hr_dept_reports");
    }

    private function _check_access() {
        if (!get_setting("module_hr")) { app_redirect("forbidden"); }
        $this->access_only_team_members();
        $perm = get_array_value($this->login_user->permissions, "hr_dept_reports");
        if (!$this->login_user->is_admin && !$perm) {
            app_redirect("forbidden");
        }
    }

    private function _require_manage() {
        $this->_check_access();
        $perm = get_array_value($this->login_user->permissions, "hr_dept_reports");
        if (!$this->login_user->is_admin && $perm === "read_only") {
            app_redirect("forbidden");
        }
    }

    function index() {
        $this->_check_access();
        $view_data = [];
        $view_data["templates_dropdown"] = $this->_templates_dropdown();
        $view_data["status_dropdown"] = [
            ""          => "-- " . app_lang("all") . " --",
            "draft"     => app_lang("hr_draft"),
            "submitted" => app_lang("hr_submitted"),
            "approved"  => app_lang("hr_approved"),
            "returned"  => app_lang("hr_returned"),
        ];
        $view_data["dept_summary"] = $this->Hr_report_submissions_model->get_dept_summary();
        return $this->template->rander("hr/dept_reports/index", $view_data);
    }

    function list_data() {
        $this->_check_access();
        $options = [
            "search"      => $this->request->getPost("search"),
            "template_id" => $this->request->getPost("template_id"),
            "status"      => $this->request->getPost("status"),
        ];
        // Non-admin: restrict to own department
        $perm = get_array_value($this->login_user->permissions, "hr_dept_reports");
        if (!$this->login_user->is_admin && $perm !== "all") {
            $profile = $this->Hr_profiles_model->get_one_where(["user_id" => $this->login_user->id]);
            if ($profile && $profile->department) {
                $options["department"] = $profile->department;
            }
        } elseif ($this->request->getPost("department")) {
            $options["department"] = $this->request->getPost("department");
        }

        $result = $this->Hr_report_submissions_model->get_details($options)->getResult();
        $list_data = [];
        foreach ($result as $row) {
            $list_data[] = $this->_make_row($row);
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(["data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found]);
    }

    private function _make_row($row) {
        $can_manage = $this->login_user->is_admin ||
            (get_array_value($this->login_user->permissions, "hr_dept_reports") && get_array_value($this->login_user->permissions, "hr_dept_reports") !== "read_only");

        $status_badges = [
            "draft"     => "secondary",
            "submitted" => "info",
            "approved"  => "success",
            "returned"  => "warning",
        ];
        $badge = $status_badges[$row->status] ?? "secondary";
        $status_label = "<span class='badge bg-{$badge}'>" . app_lang("hr_" . $row->status) . "</span>";

        $actions = "<a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-default btn-report-view'><i class='fa fa-eye'></i></a> ";
        if ($can_manage && in_array($row->status, ["draft"])) {
            $actions .= "<a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-default btn-report-edit'><i class='fa fa-pencil'></i></a> ";
            $actions .= "<a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-danger btn-report-delete ajax-btn-delete'><i class='fa fa-times'></i></a>";
        }
        if ($this->login_user->is_admin && $row->status === "submitted") {
            $actions .= " <a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-success btn-report-approve'><i class='fa fa-check'></i> " . app_lang("hr_approve") . "</a>";
            $actions .= " <a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-warning btn-report-return'><i class='fa fa-undo'></i> " . app_lang("hr_return") . "</a>";
        }

        return [
            $row->template_title,
            $row->department,
            $row->period_label,
            $row->frequency,
            $row->submitted_by_name,
            format_to_date($row->created_at),
            $status_label,
            $actions,
        ];
    }

    function modal_form() {
        $this->_require_manage();
        $id = $this->request->getPost("id");
        $submission = $this->Hr_report_submissions_model->get_one($id);
        $view_data = ["model_info" => $submission];
        $view_data["templates_dropdown"] = $this->_templates_dropdown();

        $template_id = $submission->template_id ?? null;
        if ($template_id) {
            $view_data["fields"] = $this->Hr_report_template_fields_model->get_fields($template_id);
        } else {
            $view_data["fields"] = [];
        }

        // Decode existing field_data if editing
        $view_data["field_data"] = $submission->field_data ? json_decode($submission->field_data, true) : [];

        return $this->template->view("hr/dept_reports/modal_form", $view_data);
    }

    function get_template_fields() {
        $this->_check_access();
        $template_id = $this->request->getPost("template_id");
        if (!$template_id) {
            echo json_encode(["success" => false, "fields" => []]);
            return;
        }
        $fields = $this->Hr_report_template_fields_model->get_fields($template_id);
        echo json_encode(["success" => true, "fields" => $fields]);
    }

    function save() {
        $this->_require_manage();
        $id = $this->request->getPost("id");

        // Build field_data JSON from posted field values
        $field_values = $this->request->getPost("field_values");
        $field_data   = $field_values ? json_encode($field_values) : null;

        $data = [
            "template_id"  => $this->request->getPost("template_id"),
            "department"   => $this->request->getPost("department"),
            "period_label" => $this->request->getPost("period_label"),
            "field_data"   => $field_data,
            "notes"        => $this->request->getPost("notes"),
            "status"       => $this->request->getPost("status") ?: "draft",
        ];

        if (!$id) {
            $data["submitted_by"] = $this->login_user->id;
            $data["created_at"]   = get_current_utc_time();
        } else {
            $data["updated_at"]   = get_current_utc_time();
        }

        $save_id = $this->Hr_report_submissions_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(["success" => true, "id" => $save_id, "message" => app_lang("record_saved")]);
        } else {
            echo json_encode(["success" => false, "message" => app_lang("error_occurred")]);
        }
    }

    function submit() {
        $this->_require_manage();
        $id = $this->request->getPost("id");
        $submission = $this->Hr_report_submissions_model->get_one($id);
        if (!$submission->id) {
            echo json_encode(["success" => false, "message" => app_lang("record_not_found")]);
            return;
        }
        $this->Hr_report_submissions_model->ci_save(["status" => "submitted", "submitted_at" => get_current_utc_time()], $id);
        echo json_encode(["success" => true, "message" => app_lang("record_saved")]);
    }

    function approve() {
        // Only admin or full-perm hr_dept_reports
        $this->_check_access();
        $perm = get_array_value($this->login_user->permissions, "hr_dept_reports");
        if (!$this->login_user->is_admin && $perm !== "all") {
            echo json_encode(["success" => false, "message" => app_lang("access_denied")]);
            return;
        }
        $id = $this->request->getPost("id");
        $this->Hr_report_submissions_model->ci_save([
            "status"           => "approved",
            "hod_approved_by"  => $this->login_user->id,
            "hod_approved_at"  => get_current_utc_time(),
        ], $id);
        echo json_encode(["success" => true, "message" => app_lang("record_saved")]);
    }

    function return_report() {
        $this->_check_access();
        $perm = get_array_value($this->login_user->permissions, "hr_dept_reports");
        if (!$this->login_user->is_admin && $perm !== "all") {
            echo json_encode(["success" => false, "message" => app_lang("access_denied")]);
            return;
        }
        $id = $this->request->getPost("id");
        $this->Hr_report_submissions_model->ci_save([
            "status"      => "returned",
            "return_note" => $this->request->getPost("return_note"),
            "returned_at" => get_current_utc_time(),
        ], $id);
        echo json_encode(["success" => true, "message" => app_lang("record_saved")]);
    }

    function delete() {
        $this->_require_manage();
        $id = $this->request->getPost("id");
        $this->Hr_report_submissions_model->delete($id);
        echo json_encode(["success" => true, "message" => app_lang("record_deleted")]);
    }

    // Template management (admin only)
    function templates() {
        if (!$this->login_user->is_admin) { app_redirect("forbidden"); }
        return $this->template->rander("hr/dept_reports/templates", []);
    }

    function templates_list_data() {
        if (!$this->login_user->is_admin) { app_redirect("forbidden"); }
        $options = ["search" => $this->request->getPost("search")];
        $result = $this->Hr_report_templates_model->get_details($options)->getResult();
        $list_data = [];
        foreach ($result as $row) {
            $list_data[] = [
                $row->title,
                $row->department === "ALL" ? app_lang("hr_all_departments") : $row->department,
                $row->frequency,
                $row->is_active ? "<span class='badge bg-success'>" . app_lang("active") . "</span>" : "<span class='badge bg-secondary'>" . app_lang("inactive") . "</span>",
                $row->created_by_name,
                format_to_date($row->created_at),
                "<a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-default btn-template-edit'><i class='fa fa-pencil'></i></a> "
                . "<a href='javascript:;' data-id='{$row->id}' class='btn btn-xs btn-danger btn-template-delete ajax-btn-delete'><i class='fa fa-times'></i></a>",
            ];
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(["data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found]);
    }

    function template_modal_form() {
        if (!$this->login_user->is_admin) { app_redirect("forbidden"); }
        $id = $this->request->getPost("id");
        $template = $this->Hr_report_templates_model->get_one($id);
        $view_data = ["model_info" => $template];
        if ($id) {
            $view_data["fields"] = $this->Hr_report_template_fields_model->get_fields($id);
        } else {
            $view_data["fields"] = [];
        }
        $view_data["frequency_dropdown"] = [
            "daily"     => app_lang("hr_daily"),
            "weekly"    => app_lang("hr_weekly"),
            "monthly"   => app_lang("hr_monthly"),
            "quarterly" => app_lang("hr_quarterly"),
            "annual"    => app_lang("hr_annual"),
        ];
        $view_data["field_type_dropdown"] = [
            "text"     => app_lang("hr_text"),
            "number"   => app_lang("hr_number"),
            "textarea" => app_lang("hr_textarea"),
            "select"   => app_lang("hr_select"),
            "date"     => app_lang("hr_date"),
        ];
        return $this->template->view("hr/dept_reports/template_modal_form", $view_data);
    }

    function save_template() {
        if (!$this->login_user->is_admin) { app_redirect("forbidden"); }
        $id = $this->request->getPost("id");

        $data = [
            "title"      => $this->request->getPost("title"),
            "department" => $this->request->getPost("department") ?: "ALL",
            "frequency"  => $this->request->getPost("frequency"),
            "is_active"  => $this->request->getPost("is_active") ? 1 : 0,
            "description" => $this->request->getPost("description"),
        ];

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        } else {
            $data["updated_at"] = get_current_utc_time();
        }

        $save_id = $this->Hr_report_templates_model->ci_save($data, $id);
        if (!$save_id) {
            echo json_encode(["success" => false, "message" => app_lang("error_occurred")]);
            return;
        }

        // Save fields
        $fields = $this->request->getPost("fields");
        if (is_array($fields)) {
            // Soft-delete existing fields then re-save
            $this->Hr_report_template_fields_model->delete_by_template($save_id);
            $sort = 0;
            foreach ($fields as $field) {
                if (empty($field["label"])) continue;
                $this->Hr_report_template_fields_model->ci_save([
                    "template_id" => $save_id,
                    "label"       => $field["label"],
                    "field_type"  => $field["field_type"] ?? "text",
                    "options"     => $field["options"] ?? null,
                    "is_required" => !empty($field["is_required"]) ? 1 : 0,
                    "sort_order"  => $sort++,
                    "deleted"     => 0,
                ]);
            }
        }

        echo json_encode(["success" => true, "id" => $save_id, "message" => app_lang("record_saved")]);
    }

    function delete_template() {
        if (!$this->login_user->is_admin) { app_redirect("forbidden"); }
        $id = $this->request->getPost("id");
        $this->Hr_report_templates_model->delete($id);
        echo json_encode(["success" => true, "message" => app_lang("record_deleted")]);
    }

    private function _templates_dropdown() {
        $templates = $this->Hr_report_templates_model->get_active_for_dropdown();
        $dropdown = ["" => "-- " . app_lang("select") . " --"];
        foreach ($templates as $t) {
            $dropdown[$t->id] = $t->title;
        }
        return $dropdown;
    }
}
