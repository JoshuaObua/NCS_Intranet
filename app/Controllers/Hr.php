<?php
namespace App\Controllers;

class Hr extends Security_Controller {
    function __construct() {
        parent::__construct();
        $this->init_permission_checker("hr");
    }

    // Access check: module enabled + team member + has hr permission
    private function _check_hr_access() {
        if (!get_setting("module_hr")) { app_redirect("forbidden"); }
        $this->access_only_team_members();
        if (!($this->login_user->is_admin || get_array_value($this->login_user->permissions, "hr"))) {
            app_redirect("forbidden");
        }
    }

    private function _require_manage() {
        $this->_check_hr_access();
        $perm = get_array_value($this->login_user->permissions, "hr");
        if (!$this->login_user->is_admin && $perm === "read_only") {
            app_redirect("forbidden");
        }
    }

    // HR index: overview page showing all employee profiles list
    function index() {
        $this->_check_hr_access();
        // pass departments, employment_terms dropdown, status counts
        $view_data = [];
        $depts = $this->Hr_profiles_model->get_departments();
        $dept_dropdown = ["" => "-- " . app_lang("all") . " --"];
        foreach ($depts as $d) { $dept_dropdown[$d->department] = $d->department; }
        $view_data['dept_dropdown'] = $dept_dropdown;
        $view_data['employment_terms_dropdown'] = [
            "" => "-- " . app_lang("all") . " --",
            "permanent" => app_lang("hr_permanent"),
            "contract" => app_lang("hr_contract"),
            "seconded" => app_lang("hr_seconded"),
            "casual" => app_lang("hr_casual"),
        ];
        return $this->template->rander("hr/index", $view_data);
    }

    function list_data() {
        $this->_check_hr_access();
        $options = [
            "search" => $this->request->getPost("search"),
            "department" => $this->request->getPost("department"),
        ];
        $result = $this->Hr_profiles_model->get_details($options)->getResult();
        $list_data = [];
        foreach ($result as $row) { $list_data[] = $this->_make_profile_row($row); }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(["data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found]);
    }

    private function _make_profile_row($row) {
        $can_manage = $this->login_user->is_admin || get_array_value($this->login_user->permissions, "hr") !== "read_only";
        $avatar = get_avatar($row->image);
        $name = "<a href='" . get_uri("team_members/view/" . $row->user_id . "/hr_profile") . "'>" . $avatar . " " . $row->full_name . "</a>";
        $terms_map = [
            "permanent" => "<span class='badge bg-success'>" . app_lang("hr_permanent") . "</span>",
            "contract" => "<span class='badge bg-primary'>" . app_lang("hr_contract") . "</span>",
            "seconded" => "<span class='badge bg-info'>" . app_lang("hr_seconded") . "</span>",
            "casual" => "<span class='badge bg-secondary'>" . app_lang("hr_casual") . "</span>",
        ];
        $terms_badge = get_array_value($terms_map, $row->employment_terms) ?: ($row->employment_terms ?: "-");

        // Contract expiry alert
        $expiry_alert = "";
        if ($row->contract_expiry_date && strtotime($row->contract_expiry_date) <= strtotime("+60 days")) {
            $expiry_alert = " <i data-feather='alert-triangle' class='icon-14 text-warning' title='" . app_lang("hr_contract_expiring_soon") . "'></i>";
        }

        $actions = anchor(get_uri("team_members/view/" . $row->user_id . "/hr_profile"),
            "<i data-feather='eye' class='icon-16'></i>",
            ["class" => "btn btn-sm btn-outline-primary mr5", "title" => app_lang("view")]);
        if ($can_manage) {
            $actions .= js_anchor("<i data-feather='edit-2' class='icon-16'></i>", [
                "class" => "btn btn-sm btn-outline-info",
                "title" => app_lang("edit"),
                "data-act" => "ajax-modal",
                "data-action-url" => get_uri("hr/profile_modal_form/" . $row->user_id),
                "data-title" => app_lang("hr_profile"),
            ]);
        }
        return [
            $row->staff_id ?: "-",
            $name,
            $row->department ?: "-",
            $row->job_title ?: "-",
            $terms_badge . $expiry_alert,
            $row->salary_scale ?: "-",
            $row->contract_expiry_date ? format_to_date($row->contract_expiry_date, false) : "-",
            $actions,
        ];
    }

    // Tab loaded inside team_members/view.php for a specific user
    function profile_tab($user_id = 0) {
        $this->access_only_team_members();
        if (!get_setting("module_hr")) { show_404(); }
        // Self or admin or hr permission can view
        $can_view = $this->login_user->is_admin
            || $this->login_user->id == $user_id
            || get_array_value($this->login_user->permissions, "hr");
        if (!$can_view) { app_redirect("forbidden"); }

        $view_data['user_id'] = $user_id;
        $view_data['profile'] = $this->Hr_profiles_model->get_profile_by_user($user_id);
        if (!$view_data['profile']) { $view_data['profile'] = new \stdClass(); }

        $perm = get_array_value($this->login_user->permissions, "hr");
        $view_data['can_manage'] = $this->login_user->is_admin || ($perm && $perm !== "read_only");

        // Supervisor dropdown
        $users = $this->Users_model->get_all_where(["deleted" => 0, "user_type" => "staff"])->getResult();
        $sup_dropdown = ["" => "-- " . app_lang("select") . " --"];
        foreach ($users as $u) { $sup_dropdown[$u->id] = $u->first_name . " " . $u->last_name; }
        $view_data['supervisor_dropdown'] = $sup_dropdown;

        return $this->template->view("hr/profile_tab", $view_data);
    }

    function profile_modal_form($user_id = 0) {
        $this->_require_manage();
        $view_data['user_id'] = $user_id;
        $view_data['profile'] = $this->Hr_profiles_model->get_profile_by_user($user_id);
        if (!$view_data['profile']) { $view_data['profile'] = new \stdClass(); }
        $user_info = $this->Users_model->get_one($user_id);
        $view_data['user_info'] = $user_info;

        $users = $this->Users_model->get_all_where(["deleted" => 0, "user_type" => "staff"])->getResult();
        $sup_dropdown = ["" => "-- " . app_lang("select") . " --"];
        foreach ($users as $u) { $sup_dropdown[$u->id] = $u->first_name . " " . $u->last_name; }
        $view_data['supervisor_dropdown'] = $sup_dropdown;

        $view_data['employment_terms_dropdown'] = [
            "permanent" => app_lang("hr_permanent"),
            "contract" => app_lang("hr_contract"),
            "seconded" => app_lang("hr_seconded"),
            "casual" => app_lang("hr_casual"),
        ];
        $view_data['salary_scale_dropdown'] = array_combine(
            array_map(fn($i) => "SCALE_$i", range(1,10)),
            array_map(fn($i) => "Scale $i", range(1,10))
        );
        $view_data['gender_dropdown'] = [
            "male" => app_lang("male"), "female" => app_lang("female"), "other" => app_lang("other")
        ];
        $view_data['marital_status_dropdown'] = [
            "single" => app_lang("hr_single"), "married" => app_lang("hr_married"),
            "divorced" => app_lang("hr_divorced"), "widowed" => app_lang("hr_widowed"),
        ];

        return $this->template->view("hr/profile_modal_form", $view_data);
    }

    function save_profile() {
        $this->_require_manage();
        $user_id = (int)$this->request->getPost("user_id");
        if (!$user_id) { echo json_encode(["success" => false, "message" => app_lang("error_occurred")]); return; }

        $data = [
            "staff_id" => $this->request->getPost("staff_id"),
            "nin_number" => $this->request->getPost("nin_number"),
            "passport_number" => $this->request->getPost("passport_number"),
            "gender" => $this->request->getPost("gender"),
            "marital_status" => $this->request->getPost("marital_status"),
            "disability_status" => (int)$this->request->getPost("disability_status"),
            "home_district" => $this->request->getPost("home_district"),
            "residential_address" => $this->request->getPost("residential_address"),
            "next_of_kin_name" => $this->request->getPost("next_of_kin_name"),
            "next_of_kin_phone" => $this->request->getPost("next_of_kin_phone"),
            "next_of_kin_relationship" => $this->request->getPost("next_of_kin_relationship"),
            "next_of_kin_nin" => $this->request->getPost("next_of_kin_nin"),
            "emergency_contact_name" => $this->request->getPost("emergency_contact_name"),
            "emergency_contact_phone" => $this->request->getPost("emergency_contact_phone"),
            "emergency_contact2_name" => $this->request->getPost("emergency_contact2_name"),
            "emergency_contact2_phone" => $this->request->getPost("emergency_contact2_phone"),
            "department" => $this->request->getPost("department"),
            "duty_station" => $this->request->getPost("duty_station"),
            "employment_terms" => $this->request->getPost("employment_terms"),
            "salary_scale" => $this->request->getPost("salary_scale"),
            "direct_supervisor_id" => (int)$this->request->getPost("direct_supervisor_id") ?: null,
            "tin_number" => $this->request->getPost("tin_number"),
            "nssf_number" => $this->request->getPost("nssf_number"),
            "bank_name" => $this->request->getPost("bank_name"),
            "bank_branch" => $this->request->getPost("bank_branch"),
            "bank_account_number" => $this->request->getPost("bank_account_number"),
            "bank_account_name" => $this->request->getPost("bank_account_name"),
            "notes" => $this->request->getPost("notes"),
            "updated_at" => get_current_utc_time(),
        ];

        foreach (["appointment_date", "probation_end_date", "contract_expiry_date"] as $d) {
            $data[$d] = $this->request->getPost($d) ?: null;
        }

        $exists = $this->Hr_profiles_model->get_profile_by_user($user_id);
        if (!$exists || !$exists->id) {
            $data["user_id"] = $user_id;
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Hr_profiles_model->save_profile($data, $user_id);
        if ($save_id) {
            echo json_encode(["success" => true, "message" => app_lang("record_saved")]);
        } else {
            echo json_encode(["success" => false, "message" => app_lang("error_occurred")]);
        }
    }
}
