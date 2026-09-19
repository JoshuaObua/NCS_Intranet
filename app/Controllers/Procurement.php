<?php

namespace App\Controllers;

class Procurement extends Security_Controller {

    protected $db;
    public $Procurement_form5_model;
    public $Procurement_form5_items_model;
    public $Procurement_plans_model;
    public $Procurement_suppliers_model;

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
        $this->db = \Config\Database::connect();
        $this->Procurement_form5_model = model("App\Models\Procurement_form5_model");
        $this->Procurement_form5_items_model = model("App\Models\Procurement_form5_items_model");
        $this->Procurement_plans_model = model("App\Models\Procurement_plans_model");
        $this->Procurement_suppliers_model = model("App\Models\Procurement_suppliers_model");
    }

    function index() {
        return $this->fill_form_5();
    }

    /* ─── HELPER METHODS ─────────────────────────────────────────── */

    private function _get_user_department_info($user_id) {
        $sql = "SELECT u.id AS user_id, CONCAT(u.first_name, ' ', u.last_name) AS user_name, u.role_id, u.is_admin,
                       r.title AS role_title, r.department_id, r.rank AS role_rank,
                       d.title AS department_title, d.code AS department_code
                FROM ncs_users u
                LEFT JOIN ncs_roles r ON r.id = u.role_id AND r.deleted=0
                LEFT JOIN ncs_departments d ON d.id = r.department_id AND d.deleted=0
                WHERE u.id = $user_id AND u.deleted=0";
        $row = $this->db->query($sql)->getRow();

        if (!$row) {
            $row = new \stdClass();
            $row->user_id = $user_id;
            $row->user_name = $this->login_user->first_name . " " . $this->login_user->last_name;
            $row->department_id = 7;
            $row->role_rank = 3;
            $row->role_title = "Assistant Estate Officer";
            $row->department_title = "Estate & Facilities Management";
            $row->department_code = "EST";
        }
        if (!$row->department_id) {
            $row->department_id = 7;
            $row->department_title = "Estate & Facilities Management";
            $row->department_code = "EST";
        }
        if (!$row->role_rank) {
            $row->role_rank = ($this->login_user->is_admin ? 1 : 3);
            $row->role_title = ($this->login_user->is_admin ? "General Secretary / Admin" : "Officer");
        }
        return $row;
    }

    private function _get_superior_dropdown($department_id, $user_rank) {
        $target_rank = max(1, $user_rank - 1);

        $sql = "SELECT u.id AS user_id, CONCAT(u.first_name, ' ', u.last_name) AS officer_name,
                       r.id AS role_id, r.title AS role_title, r.rank, d.title AS department_title
                FROM ncs_roles r
                JOIN ncs_departments d ON d.id = r.department_id
                LEFT JOIN ncs_users u ON u.role_id = r.id AND u.deleted=0
                WHERE r.department_id = $department_id
                  AND r.rank = $target_rank
                  AND r.deleted=0
                ORDER BY r.rank ASC";
        $results = $this->db->query($sql)->getResult();

        if (empty($results)) {
            $sql = "SELECT u.id AS user_id, CONCAT(u.first_name, ' ', u.last_name) AS officer_name,
                           r.id AS role_id, r.title AS role_title, r.rank, d.title AS department_title
                    FROM ncs_roles r
                    JOIN ncs_departments d ON d.id = r.department_id
                    LEFT JOIN ncs_users u ON u.role_id = r.id AND u.deleted=0
                    WHERE r.department_id = $department_id
                      AND r.rank < $user_rank
                      AND r.deleted=0
                    ORDER BY r.rank ASC";
            $results = $this->db->query($sql)->getResult();
        }

        if (empty($results)) {
            $sql = "SELECT u.id AS user_id, CONCAT(u.first_name, ' ', u.last_name) AS officer_name,
                           r.id AS role_id, r.title AS role_title, r.rank, d.title AS department_title
                    FROM ncs_roles r
                    JOIN ncs_departments d ON d.id = r.department_id
                    LEFT JOIN ncs_users u ON u.role_id = r.id AND u.deleted=0
                    WHERE r.department_id = $department_id
                      AND r.deleted=0
                    ORDER BY r.rank ASC";
            $results = $this->db->query($sql)->getResult();
        }

        $dropdown = array();
        foreach ($results as $row) {
            $key = $row->user_id ? $row->user_id : ("role_" . $row->role_id);
            $name = $row->officer_name ? $row->officer_name . " (" . $row->role_title . " - Rank " . $row->rank . ")" : $row->role_title . " (Rank " . $row->rank . ")";
            $dropdown[$key] = $name;
        }

        return $dropdown;
    }

    function get_department_officers() {
        $department_id = (int)$this->request->getPost('department_id');
        $sql = "SELECT u.id AS user_id, CONCAT(u.first_name, ' ', u.last_name) AS officer_name,
                       r.id AS role_id, r.title AS role_title, r.rank, d.title AS department_title
                FROM ncs_roles r
                JOIN ncs_departments d ON d.id = r.department_id
                LEFT JOIN ncs_users u ON u.role_id = r.id AND u.deleted=0
                WHERE r.department_id = $department_id AND r.deleted=0
                ORDER BY r.rank ASC, r.title ASC";
        $results = $this->db->query($sql)->getResult();

        $options = array();
        foreach ($results as $row) {
            $key = $row->user_id ? $row->user_id : ("role_" . $row->role_id);
            $text = $row->officer_name ? $row->officer_name . " — " . $row->role_title . " (Rank " . $row->rank . ")" : $row->role_title . " (Rank " . $row->rank . ")";
            $options[] = array("id" => $key, "text" => $text);
        }

        echo json_encode(array("success" => true, "options" => $options));
    }

    /* ─── FORM 5 REQUISITIONS ────────────────────────────────────── */

    function fill_form_5() {
        $user_dept_info = $this->_get_user_department_info($this->login_user->id);
        $view_data['user_dept_info'] = $user_dept_info;
        
        $view_data['superiors_dropdown'] = $this->_get_superior_dropdown($user_dept_info->department_id, $user_dept_info->role_rank);
        $view_data['departments_dropdown'] = $this->Departments_model->get_department_dropdown();

        $view_data['procurement_type_dropdown'] = array(
            "SUPPLIES"                 => "Supplies",
            "WORKS"                    => "Works",
            "NON_CONSULTANCY_SERVICES" => "Non-Consultancy Services",
            "CONSULTANCY_SERVICES"     => "Consultancy Services"
        );

        $view_data['budget_category_dropdown'] = array(
            "RECURRENT_BUDGET"   => "Recurrent Budget",
            "DEVELOPMENT_BUDGET" => "Development Budget"
        );

        $count = count($this->Procurement_form5_model->get_details()->getResult()) + 1;
        $view_data['sequence_number'] = str_pad($count, 5, "0", STR_PAD_LEFT);

        return $this->template->rander("procurement/fill_form_5", $view_data);
    }

    function save_form_5() {
        $this->validate_submitted_data(array(
            "procurement_type"       => "required",
            "subject_of_procurement" => "required",
            "procurement_plan_ref"   => "required",
            "location_for_delivery"  => "required",
            "date_required"          => "required"
        ));

        $user_dept_info = $this->_get_user_department_info($this->login_user->id);

        $id = $this->request->getPost('id');
        $procurement_type = $this->request->getPost('procurement_type');
        $financial_year = $this->request->getPost('financial_year') ?: "2026/2027";
        $seq = (int)$this->request->getPost('sequence_number') ?: (count($this->Procurement_form5_model->get_details()->getResult()) + 1);

        $ref_no = "NCS/" . strtoupper(substr($procurement_type, 0, 4)) . "/" . str_replace("/", "-", $financial_year) . "/" . str_pad($seq, 5, "0", STR_PAD_LEFT);

        $items_json = $this->request->getPost('items_data');
        $items = json_decode($items_json, true) ?: array();

        $grand_total = 0.00;
        foreach ($items as $item) {
            $qty = floatval(get_array_value($item, "quantity"));
            $cost = floatval(get_array_value($item, "estimated_unit_cost"));
            $grand_total += ($qty * $cost);
        }

        $assigned_approver = $this->request->getPost('assigned_approver_id');
        $assigned_approver_id = (is_numeric($assigned_approver)) ? (int)$assigned_approver : 0;

        $history = array(
            array(
                "timestamp"     => date("Y-m-d H:i:s"),
                "action"        => "SUBMITTED",
                "from_user"     => $user_dept_info->user_name,
                "from_dept"     => $user_dept_info->department_title,
                "from_rank"     => $user_dept_info->role_rank,
                "to_approver"   => $assigned_approver,
                "comments"      => "Initial Form 5 Requisition Submission"
            )
        );

        $data = array(
            "procurement_ref_no"        => $ref_no,
            "pde_code"                  => "NCS",
            "procurement_type"          => $procurement_type,
            "financial_year"            => $financial_year,
            "sequence_number"           => $seq,
            "budget_category"           => $this->request->getPost('budget_category') ?: "RECURRENT_BUDGET",
            "recurrent_budget_code"     => $this->request->getPost('recurrent_budget_code'),
            "development_budget_code"   => $this->request->getPost('development_budget_code'),
            "project_code"              => $this->request->getPost('project_code'),
            "project_title"             => $this->request->getPost('project_title'),
            "is_multiyear"              => $this->request->getPost('is_multiyear') ? true : false,
            "required_ugx_yr1"          => unformat_currency($this->request->getPost('required_ugx_yr1')),
            "required_ugx_yr2"          => unformat_currency($this->request->getPost('required_ugx_yr2')),
            "required_ugx_yr3"          => unformat_currency($this->request->getPost('required_ugx_yr3')),
            "required_ugx_yr4"          => unformat_currency($this->request->getPost('required_ugx_yr4')),
            "subject_of_procurement"    => $this->request->getPost('subject_of_procurement'),
            "procurement_plan_ref"      => $this->request->getPost('procurement_plan_ref'),
            "location_for_delivery"     => $this->request->getPost('location_for_delivery'),
            "date_required"             => $this->request->getPost('date_required'),
            "currency"                  => "UGX",
            "grand_total_estimated_cost"=> $grand_total,
            "status"                    => "SUBMITTED",
            "requester_user_id"         => $this->login_user->id,
            "department_id"             => $user_dept_info->department_id,
            "requester_rank"            => $user_dept_info->role_rank,
            "assigned_approver_id"      => $assigned_approver_id,
            "target_department_id"      => $user_dept_info->department_id,
            "workflow_history"          => json_encode($history),
            "requested_at"              => date("Y-m-d H:i:s")
        );

        $save_id = $this->Procurement_form5_model->ci_save($data, $id);

        if ($save_id) {
            if ($id) {
                $this->db->query("DELETE FROM ncs_procurement_form5_items WHERE form5_id=$id");
            }
            $target_id = $id ? $id : $save_id;

            $item_no = 1;
            foreach ($items as $item) {
                $item_data = array(
                    "form5_id"            => $target_id,
                    "item_no"             => $item_no++,
                    "description"         => get_array_value($item, "description"),
                    "quantity"            => floatval(get_array_value($item, "quantity")),
                    "unit_of_measure"     => get_array_value($item, "unit_of_measure"),
                    "estimated_unit_cost" => floatval(get_array_value($item, "estimated_unit_cost")),
                    "market_price"        => floatval(get_array_value($item, "market_price")),
                    "line_total_cost"     => floatval(get_array_value($item, "quantity")) * floatval(get_array_value($item, "estimated_unit_cost"))
                );
                $this->Procurement_form5_items_model->ci_save($item_data);
            }

            echo json_encode(array("success" => true, "id" => $target_id, "message" => app_lang('record_saved'), "redirect_to" => get_uri("procurement/approvals")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── FORM 5 APPROVALS ────────────────────────────────────────── */

    function approvals() {
        $view_data['summary'] = $this->Procurement_form5_model->get_summary_stats();
        return $this->template->rander("procurement/approvals", $view_data);
    }

    function approvals_list_data() {
        $list_data = $this->Procurement_form5_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_form5_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_form5_row($data) {
        $status_badge = "<span class='badge bg-secondary'>Draft</span>";
        if ($data->status === "SUBMITTED") {
            $status_badge = "<span class='badge bg-warning'>Submitted (Internal Dept)</span>";
        } else if ($data->status === "HOD_CONFIRMED") {
            $status_badge = "<span class='badge bg-info'>HOD Endorsed</span>";
        } else if ($data->status === "FORWARDED_DEPT") {
            $status_badge = "<span class='badge bg-primary'>Forwarded Inter-Dept</span>";
        } else if ($data->status === "VOTE_CLEARED") {
            $status_badge = "<span class='badge bg-info'>Vote Cleared</span>";
        } else if ($data->status === "APPROVED") {
            $status_badge = "<span class='badge bg-success'>Approved</span>";
        } else if ($data->status === "REJECTED") {
            $status_badge = "<span class='badge bg-danger'>Rejected</span>";
        }

        $actions = modal_anchor(get_uri("procurement/view_form5_modal"), "<i data-feather='eye' class='icon-16'></i>", array("class" => "edit", "title" => "View Form 5 Details", "data-post-id" => $data->id));
        if ($data->status !== "APPROVED" && $data->status !== "REJECTED") {
            $actions .= " " . modal_anchor(get_uri("procurement/approval_modal"), "<i data-feather='check-square' class='icon-16'></i>", array("class" => "edit text-primary", "title" => "Process Approval / Forward", "data-post-id" => $data->id));
        }

        return array(
            $data->id,
            "<strong>" . $data->procurement_ref_no . "</strong>",
            $data->subject_of_procurement,
            $data->procurement_type,
            to_currency($data->grand_total_estimated_cost),
            $data->requester_name ? $data->requester_name : "NCS Officer",
            format_to_date($data->requested_at),
            $status_badge,
            $actions
        );
    }

    function view_form5_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Procurement_form5_model->get_details(array("id" => $id))->getRow();
        $view_data['items'] = $this->Procurement_form5_items_model->get_details(array("form5_id" => $id))->getResult();
        $view_data['workflow_history'] = json_decode($view_data['model_info']->workflow_history, true) ?: array();

        return $this->template->view("procurement/form_5_view_modal", $view_data);
    }

    function approval_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Procurement_form5_model->get_details(array("id" => $id))->getRow();
        $view_data['departments_dropdown'] = $this->Departments_model->get_department_dropdown();
        
        $user_dept_info = $this->_get_user_department_info($this->login_user->id);
        $view_data['superiors_dropdown'] = $this->_get_superior_dropdown($user_dept_info->department_id, $user_dept_info->role_rank);

        return $this->template->view("procurement/approval_modal", $view_data);
    }

    function save_approval() {
        $id = $this->request->getPost('id');
        $action = $this->request->getPost('action'); // 'FORWARD_SUPERIOR', 'FORWARD_DEPT', 'FINAL_APPROVE', 'REJECT'
        $comments = $this->request->getPost('comments');

        $model = $this->Procurement_form5_model->get_one($id);
        if (!$model->id) {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
            return;
        }

        $user_dept_info = $this->_get_user_department_info($this->login_user->id);
        $history = json_decode($model->workflow_history, true) ?: array();

        $data = array();
        if ($action === "FORWARD_SUPERIOR") {
            $assigned_approver = $this->request->getPost('assigned_approver_id');
            $data['status'] = "HOD_CONFIRMED";
            $data['assigned_approver_id'] = is_numeric($assigned_approver) ? (int)$assigned_approver : 0;
            $data['hod_user_id'] = $this->login_user->id;
            $data['hod_approval_status'] = "CONFIRMED";
            $data['hod_comments'] = $comments;
            $data['hod_decided_at'] = date("Y-m-d H:i:s");

            $history[] = array(
                "timestamp"   => date("Y-m-d H:i:s"),
                "action"      => "ENDORSED_AND_SUBMITTED_SUPERIOR",
                "from_user"   => $user_dept_info->user_name,
                "from_dept"   => $user_dept_info->department_title,
                "to_approver" => $assigned_approver,
                "comments"    => $comments
            );
        } else if ($action === "FORWARD_DEPT") {
            $target_dept_id = (int)$this->request->getPost('target_department_id');
            $target_officer = $this->request->getPost('target_officer_id');

            $data['status'] = "FORWARDED_DEPT";
            $data['target_department_id'] = $target_dept_id;
            $data['assigned_approver_id'] = is_numeric($target_officer) ? (int)$target_officer : 0;

            $dept_model = $this->Departments_model->get_one($target_dept_id);
            $target_dept_title = $dept_model->id ? $dept_model->title : "Target Department";

            $history[] = array(
                "timestamp"   => date("Y-m-d H:i:s"),
                "action"      => "FORWARDED_TO_DEPARTMENT",
                "from_user"   => $user_dept_info->user_name,
                "from_dept"   => $user_dept_info->department_title,
                "to_dept"     => $target_dept_title,
                "to_officer"  => $target_officer,
                "comments"    => $comments
            );
        } else if ($action === "FINAL_APPROVE") {
            $data['status'] = "APPROVED";
            $data['accounting_officer_user_id'] = $this->login_user->id;
            $data['accounting_officer_status'] = "APPROVED";
            $data['accounting_officer_comments'] = $comments;
            $data['accounting_officer_decided_at'] = date("Y-m-d H:i:s");

            $history[] = array(
                "timestamp"   => date("Y-m-d H:i:s"),
                "action"      => "STATUTORY_FINAL_APPROVED",
                "from_user"   => $user_dept_info->user_name,
                "from_dept"   => $user_dept_info->department_title,
                "comments"    => $comments
            );
        } else if ($action === "REJECT") {
            $data['status'] = "REJECTED";
            $data['hod_comments'] = $comments;

            $history[] = array(
                "timestamp"   => date("Y-m-d H:i:s"),
                "action"      => "REJECTED",
                "from_user"   => $user_dept_info->user_name,
                "from_dept"   => $user_dept_info->department_title,
                "comments"    => $comments
            );
        }

        $data['workflow_history'] = json_encode($history);

        $this->Procurement_form5_model->ci_save($data, $id);
        echo json_encode(array("success" => true, "data" => $this->_make_form5_row($this->Procurement_form5_model->get_details(array("id" => $id))->getRow()), "id" => $id, "message" => app_lang('record_saved')));
    }

    /* ─── ANNUAL PROCUREMENT PLAN (APP) ───────────────────────────── */

    function plan() {
        return $this->template->rander("procurement/plan");
    }

    function plan_list_data() {
        $list_data = $this->Procurement_plans_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_plan_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_plan_row($data) {
        $status_badge = "<span class='badge bg-info'>" . $data->status . "</span>";
        if ($data->status === "AWARDED") {
            $status_badge = "<span class='badge bg-success'>AWARDED</span>";
        } else if ($data->status === "INITIATED") {
            $status_badge = "<span class='badge bg-warning'>INITIATED</span>";
        }

        $actions = modal_anchor(get_uri("procurement/plan_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit APP Entry", "data-post-id" => $data->id));

        return array(
            $data->id,
            $data->financial_year,
            "<strong>" . $data->procurement_ref_no . "</strong>",
            $data->subject_of_procurement,
            $data->procurement_type,
            $data->procurement_method,
            to_currency($data->estimated_cost),
            $data->user_department,
            format_to_date($data->planned_invitation_date),
            $status_badge,
            $actions
        );
    }

    function plan_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Procurement_plans_model->get_one($id);
        $view_data['departments_dropdown'] = $this->Departments_model->get_department_dropdown();

        return $this->template->view("procurement/plan_modal_form", $view_data);
    }

    function save_plan() {
        $this->validate_submitted_data(array(
            "financial_year"         => "required",
            "subject_of_procurement" => "required",
            "procurement_type"       => "required",
            "procurement_method"     => "required",
            "estimated_cost"         => "required",
            "user_department"        => "required"
        ));

        $id = $this->request->getPost('id');
        $procurement_type = $this->request->getPost('procurement_type');
        $financial_year = $this->request->getPost('financial_year');

        $ref_no = $this->request->getPost('procurement_ref_no');
        if (!$ref_no) {
            $count = count($this->Procurement_plans_model->get_details()->getResult()) + 1;
            $ref_no = "NCS/" . strtoupper(substr($procurement_type, 0, 4)) . "/" . str_replace("/", "-", $financial_year) . "/" . str_pad($count, 5, "0", STR_PAD_LEFT);
        }

        $data = array(
            "financial_year"                  => $financial_year,
            "procurement_ref_no"              => $ref_no,
            "subject_of_procurement"          => $this->request->getPost('subject_of_procurement'),
            "procurement_type"                => $procurement_type,
            "procurement_method"              => $this->request->getPost('procurement_method'),
            "estimated_cost"                  => unformat_currency($this->request->getPost('estimated_cost')),
            "user_department"                 => $this->request->getPost('user_department'),
            "planned_invitation_date"         => $this->request->getPost('planned_invitation_date'),
            "planned_contract_signature_date" => $this->request->getPost('planned_contract_signature_date'),
            "status"                          => $this->request->getPost('status') ? $this->request->getPost('status') : "PLANNED"
        );

        $save_id = $this->Procurement_plans_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_plan_row($this->Procurement_plans_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── SUPPLIER REGISTRY ───────────────────────────────────────── */

    function suppliers() {
        return $this->template->rander("procurement/suppliers");
    }

    function suppliers_list_data() {
        $list_data = $this->Procurement_suppliers_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_supplier_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_supplier_row($data) {
        $is_bl = ($data->is_blacklisted === true || $data->is_blacklisted === "t" || $data->is_blacklisted === "1" || $data->is_blacklisted == 1);
        $blacklist_status = "<span class='badge bg-success'>Active / Compliant</span>";
        if ($is_bl) {
            $blacklist_status = "<span class='badge bg-danger' title='" . htmlspecialchars($data->blacklist_reason ? $data->blacklist_reason : "PPDA Debarred") . "'>Debarred / Blacklisted</span>";
        }

        $actions = modal_anchor(get_uri("procurement/supplier_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Supplier Details", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->company_name . "</strong>",
            $data->ppda_registration_no,
            $data->tin_number,
            $data->contact_person,
            $data->contact_email . "<br><small class='text-muted'>" . $data->contact_phone . "</small>",
            $blacklist_status,
            $actions
        );
    }

    function supplier_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Procurement_suppliers_model->get_one($id);

        return $this->template->view("procurement/supplier_modal_form", $view_data);
    }

    function save_supplier() {
        $this->validate_submitted_data(array(
            "company_name"         => "required",
            "ppda_registration_no" => "required",
            "tin_number"           => "required"
        ));

        $id = $this->request->getPost('id');

        $data = array(
            "company_name"         => $this->request->getPost('company_name'),
            "ppda_registration_no" => $this->request->getPost('ppda_registration_no'),
            "tin_number"           => $this->request->getPost('tin_number'),
            "contact_person"       => $this->request->getPost('contact_person'),
            "contact_email"        => $this->request->getPost('contact_email'),
            "contact_phone"        => $this->request->getPost('contact_phone'),
            "is_blacklisted"       => $this->request->getPost('is_blacklisted') ? true : false,
            "blacklist_reason"     => $this->request->getPost('blacklist_reason')
        );

        $save_id = $this->Procurement_suppliers_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_supplier_row($this->Procurement_suppliers_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── STATUTORY PROCUREMENT REPORTS ─────────────────────────── */

    function reports() {
        $view_data['summary'] = $this->Procurement_form5_model->get_summary_stats();
        $view_data['total_suppliers'] = count($this->Procurement_suppliers_model->get_details()->getResult());
        $view_data['debarred_suppliers'] = count($this->Procurement_suppliers_model->get_details(array("is_blacklisted" => true))->getResult());
        $view_data['app_items'] = $this->Procurement_plans_model->get_details()->getResult();

        return $this->template->rander("procurement/reports", $view_data);
    }
}
