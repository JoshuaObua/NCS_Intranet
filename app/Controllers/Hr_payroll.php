<?php
namespace App\Controllers;

use App\Models\Hr_payroll_model;

class Hr_payroll extends Security_Controller {
    function __construct() {
        parent::__construct();
        $this->init_permission_checker("hr");
    }

    private function _check_access() {
        if (!get_setting("module_hr")) { app_redirect("forbidden"); }
        $this->access_only_team_members();
        $perm = get_array_value($this->login_user->permissions, "hr");
        $payroll_perm = get_array_value($this->login_user->permissions, "hr_payroll");
        if (!($this->login_user->is_admin || $perm || $payroll_perm)) {
            app_redirect("forbidden");
        }
    }

    private function _require_manage() {
        $this->_check_access();
        $perm = get_array_value($this->login_user->permissions, "hr");
        if (!$this->login_user->is_admin && $perm === "read_only") {
            app_redirect("forbidden");
        }
    }

    function index() {
        $this->_check_access();
        $current_year = date('Y');
        $view_data['years'] = array_combine(range($current_year, $current_year-5), range($current_year, $current_year-5));
        $view_data['months'] = [
            1=>"January",2=>"February",3=>"March",4=>"April",
            5=>"May",6=>"June",7=>"July",8=>"August",
            9=>"September",10=>"October",11=>"November",12=>"December"
        ];
        $view_data['status_dropdown'] = [
            "" => "-- " . app_lang("all") . " --",
            "draft" => app_lang("hr_payroll_draft"),
            "verified" => app_lang("hr_payroll_verified"),
            "approved" => app_lang("hr_payroll_approved"),
            "paid" => app_lang("hr_payroll_paid"),
        ];
        $view_data['current_month'] = (int)date('n');
        $view_data['current_year'] = $current_year;
        // Summary for current period
        $view_data['period_summary'] = $this->Hr_payroll_model->get_period_summary($current_year, (int)date('n'));
        return $this->template->rander("hr/payroll/index", $view_data);
    }

    function list_data() {
        $this->_check_access();
        $options = [
            "period_year" => $this->request->getPost("period_year"),
            "period_month" => $this->request->getPost("period_month"),
            "status" => $this->request->getPost("status"),
            "search" => $this->request->getPost("search"),
        ];
        $result = $this->Hr_payroll_model->get_details($options)->getResult();
        $list_data = [];
        foreach ($result as $row) { $list_data[] = $this->_make_row($row); }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(["data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found]);
    }

    private function _make_row($row) {
        $status_map = [
            "draft" => "secondary", "verified" => "info",
            "approved" => "primary", "paid" => "success",
        ];
        $badge_color = get_array_value($status_map, $row->status) ?: "light";
        $status_badge = "<span class='badge bg-{$badge_color}'>" . app_lang("hr_payroll_" . $row->status) . "</span>";

        $can_manage = $this->login_user->is_admin ||
            (get_array_value($this->login_user->permissions, "hr") && get_array_value($this->login_user->permissions, "hr") !== "read_only");

        $actions = anchor(get_uri("hr_payroll/payslip/" . $row->id),
            "<i data-feather='file-text' class='icon-16'></i>",
            ["class" => "btn btn-sm btn-outline-primary mr5", "title" => app_lang("hr_payslip"), "target" => "_blank"]);
        if ($can_manage) {
            $actions .= js_anchor("<i data-feather='edit-2' class='icon-16'></i>", [
                "class" => "btn btn-sm btn-outline-info mr5",
                "title" => app_lang("edit"),
                "data-act" => "ajax-modal",
                "data-action-url" => get_uri("hr_payroll/modal_form/" . $row->id),
                "data-title" => app_lang("hr_edit_payroll"),
            ]);
            if (in_array($row->status, ["draft", "verified"])) {
                $actions .= js_anchor("<i data-feather='trash-2' class='icon-16'></i>", [
                    "class" => "btn btn-sm btn-outline-danger",
                    "title" => app_lang("delete"),
                    "data-action-url" => get_uri("hr_payroll/delete/" . $row->id),
                    "data-action" => "delete-confirmation",
                ]);
            }
        }

        $months = ["","January","February","March","April","May","June","July","August","September","October","November","December"];
        $period = ($months[$row->period_month] ?? $row->period_month) . " " . $row->period_year;

        return [
            $row->staff_id ?: "-",
            $row->full_name,
            $row->department ?: "-",
            $period,
            number_format($row->basic_salary, 2),
            number_format($row->gross_salary, 2),
            number_format($row->total_deductions, 2),
            number_format($row->net_salary, 2),
            $status_badge,
            $actions,
        ];
    }

    function modal_form($id = 0) {
        $this->_require_manage();
        $model_info = $id ? $this->Hr_payroll_model->get_one($id) : new \stdClass();

        // Build employee dropdown - only staff with HR profiles
        $users = $this->Users_model->get_all_where(["deleted" => 0, "user_type" => "staff"])->getResult();
        $employee_dropdown = ["" => "-- " . app_lang("select") . " --"];
        foreach ($users as $u) {
            $employee_dropdown[$u->id] = $u->first_name . " " . $u->last_name;
        }
        $view_data['employee_dropdown'] = $employee_dropdown;
        $view_data['model_info'] = $model_info;

        $view_data['months'] = [
            1=>"January",2=>"February",3=>"March",4=>"April",
            5=>"May",6=>"June",7=>"July",8=>"August",
            9=>"September",10=>"October",11=>"November",12=>"December"
        ];
        $view_data['current_month'] = (int)date('n');
        $view_data['current_year'] = date('Y');
        $view_data['status_dropdown'] = [
            "draft" => app_lang("hr_payroll_draft"),
            "verified" => app_lang("hr_payroll_verified"),
            "approved" => app_lang("hr_payroll_approved"),
            "paid" => app_lang("hr_payroll_paid"),
        ];

        return $this->template->view("hr/payroll/modal_form", $view_data);
    }

    function save() {
        $this->_require_manage();
        $id = $this->request->getPost("id");
        $user_id = (int)$this->request->getPost("user_id");
        $month = (int)$this->request->getPost("period_month");
        $year = (int)$this->request->getPost("period_year");

        // Check duplicate for new records
        if (!$id) {
            $exists = $this->Hr_payroll_model->exists_for_period($user_id, $year, $month);
            if ($exists) {
                echo json_encode(["success" => false, "message" => app_lang("hr_payroll_duplicate_period")]);
                return;
            }
        }

        $basic = (float)str_replace(",", "", $this->request->getPost("basic_salary"));
        $housing = (float)str_replace(",", "", $this->request->getPost("housing_allowance"));
        $transport = (float)str_replace(",", "", $this->request->getPost("transport_allowance"));
        $responsibility = (float)str_replace(",", "", $this->request->getPost("responsibility_allowance"));
        $overtime = (float)str_replace(",", "", $this->request->getPost("overtime_allowance"));
        $field_hon = (float)str_replace(",", "", $this->request->getPost("field_honorarium"));
        $medical = (float)str_replace(",", "", $this->request->getPost("medical_allowance"));
        $sitting = (float)str_replace(",", "", $this->request->getPost("sitting_allowance"));
        $other_allow = (float)str_replace(",", "", $this->request->getPost("other_allowances"));

        $gross = $basic + $housing + $transport + $responsibility + $overtime + $field_hon + $medical + $sitting + $other_allow;
        $paye = Hr_payroll_model::calculate_paye($gross);
        $nssf_emp = round($gross * 0.05, 2);
        $nssf_employer = round($gross * 0.10, 2);

        $lst = (float)str_replace(",", "", $this->request->getPost("lst_deduction"));
        $sacco_sav = (float)str_replace(",", "", $this->request->getPost("sacco_savings"));
        $sacco_loan = (float)str_replace(",", "", $this->request->getPost("sacco_loan"));
        $bank_loan = (float)str_replace(",", "", $this->request->getPost("bank_loan"));
        $advance = (float)str_replace(",", "", $this->request->getPost("salary_advance"));
        $union = (float)str_replace(",", "", $this->request->getPost("union_dues"));
        $other_ded = (float)str_replace(",", "", $this->request->getPost("other_deductions"));

        $total_ded = $paye + $nssf_emp + $lst + $sacco_sav + $sacco_loan + $bank_loan + $advance + $union + $other_ded;
        $net = $gross - $total_ded;

        $data = [
            "user_id" => $user_id,
            "period_month" => $month,
            "period_year" => $year,
            "basic_salary" => $basic,
            "housing_allowance" => $housing,
            "transport_allowance" => $transport,
            "responsibility_allowance" => $responsibility,
            "overtime_allowance" => $overtime,
            "field_honorarium" => $field_hon,
            "medical_allowance" => $medical,
            "sitting_allowance" => $sitting,
            "other_allowances" => $other_allow,
            "gross_salary" => $gross,
            "paye_tax" => $paye,
            "nssf_employee" => $nssf_emp,
            "nssf_employer" => $nssf_employer,
            "lst_deduction" => $lst,
            "sacco_savings" => $sacco_sav,
            "sacco_loan" => $sacco_loan,
            "bank_loan" => $bank_loan,
            "salary_advance" => $advance,
            "union_dues" => $union,
            "other_deductions" => $other_ded,
            "total_deductions" => $total_ded,
            "net_salary" => $net,
            "status" => $this->request->getPost("status") ?: "draft",
            "notes" => $this->request->getPost("notes"),
        ];

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Hr_payroll_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(["success" => true, "message" => app_lang("record_saved")]);
        } else {
            echo json_encode(["success" => false, "message" => app_lang("error_occurred")]);
        }
    }

    function delete($id = 0) {
        $this->_require_manage();
        if ($this->Hr_payroll_model->delete_one($id)) {
            echo json_encode(["success" => true, "message" => app_lang("record_deleted")]);
        } else {
            echo json_encode(["success" => false, "message" => app_lang("error_occurred")]);
        }
    }

    function payslip($id = 0) {
        $this->_check_access();
        $result = $this->Hr_payroll_model->get_details(["id" => $id])->getResult();
        if (!$result) { app_redirect("forbidden"); }
        $row = $result[0];
        // Users can view their own payslip
        if (!$this->login_user->is_admin
            && !get_array_value($this->login_user->permissions, "hr")
            && !get_array_value($this->login_user->permissions, "hr_payroll")
            && $this->login_user->id != $row->user_id) {
            app_redirect("forbidden");
        }
        $months = ["","January","February","March","April","May","June","July","August","September","October","November","December"];
        $view_data['payroll'] = $row;
        $view_data['period_label'] = ($months[$row->period_month] ?? $row->period_month) . " " . $row->period_year;
        return $this->template->rander("hr/payroll/payslip", $view_data);
    }

    function approve($id = 0) {
        $this->_require_manage();
        $data = [
            "status" => "approved",
            "approved_by" => $this->login_user->id,
            "approved_at" => get_current_utc_time(),
        ];
        if ($this->Hr_payroll_model->ci_save($data, $id)) {
            echo json_encode(["success" => true, "message" => app_lang("hr_payroll_approved")]);
        } else {
            echo json_encode(["success" => false, "message" => app_lang("error_occurred")]);
        }
    }
}
