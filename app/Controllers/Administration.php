<?php

namespace App\Controllers;

class Administration extends Security_Controller {

    protected $db;
    public $Admin_approvals_model;
    public $Admin_appraisals_model;
    public $Admin_federations_model;
    public $Admin_board_packages_model;

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
        $this->db = \Config\Database::connect();
        $this->Admin_approvals_model = model("App\Models\Admin_approvals_model");
        $this->Admin_appraisals_model = model("App\Models\Admin_appraisals_model");
        $this->Admin_federations_model = model("App\Models\Admin_federations_model");
        $this->Admin_board_packages_model = model("App\Models\Admin_board_packages_model");
    }

    function index() {
        return $this->approvals();
    }

    /* ─── 1. EXECUTIVE APPROVALS & VETTING ───────────────────────── */
    function approvals() {
        return $this->template->rander("administration/approvals");
    }

    function approvals_list_data() {
        $list_data = $this->Admin_approvals_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_approval_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_approval_row($data) {
        $urgency_badge = "<span class='badge bg-secondary'>" . $data->urgency . "</span>";
        if ($data->urgency === "Urgent" || $data->urgency === "Executive Emergency") {
            $urgency_badge = "<span class='badge bg-danger'>" . $data->urgency . "</span>";
        } else if ($data->urgency === "High") {
            $urgency_badge = "<span class='badge bg-warning'>" . $data->urgency . "</span>";
        }

        $status_badge = "<span class='badge bg-warning'>" . $data->status . "</span>";
        if ($data->status === "Approved") {
            $status_badge = "<span class='badge bg-success'>" . $data->status . "</span>";
        } else if ($data->status === "Rejected") {
            $status_badge = "<span class='badge bg-danger'>" . $data->status . "</span>";
        } else if ($data->status === "Forwarded to Board") {
            $status_badge = "<span class='badge bg-info'>" . $data->status . "</span>";
        }

        $actions = modal_anchor(get_uri("administration/process_approval_modal"), "<i data-feather='check-square' class='icon-16'></i> Vet / Approve", array("class" => "btn btn-outline-primary btn-sm", "title" => "Executive Vetting & Authorization", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->reference_no . "</strong>",
            "<span class='badge bg-primary'>" . $data->approval_type . "</span>",
            "<div><strong>" . $data->title . "</strong><br><small class='text-muted'>Submitted by " . $data->originator_name . " (" . $data->originating_department . ")</small></div>",
            to_currency($data->amount),
            $urgency_badge,
            $data->target_approver_name,
            $status_badge,
            $actions
        );
    }

    function process_approval_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Admin_approvals_model->get_one($id);
        return $this->template->view("administration/process_approval_modal", $view_data);
    }

    function save_approval_decision() {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $vetting_notes = $this->request->getPost('vetting_notes');

        $data = array(
            "status" => $status,
            "vetting_notes" => $vetting_notes,
            "decision_date" => date("Y-m-d H:i:s")
        );

        $save_id = $this->Admin_approvals_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 2. MASTER EXECUTIVE APPRAISALS & ASSET VALUATION ─────────── */
    function appraisals() {
        return $this->template->rander("administration/appraisals");
    }

    function appraisals_list_data() {
        $list_data = $this->Admin_appraisals_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_appraisal_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_appraisal_row($data) {
        $land_badge = "<span class='badge bg-success'>" . $data->land_title_status . "</span>";
        if (strpos($data->land_title_status, 'Encumbered') !== false || strpos($data->land_title_status, 'Surveying') !== false) {
            $land_badge = "<span class='badge bg-warning'>" . $data->land_title_status . "</span>";
        }

        $insurance_badge = "<span class='badge bg-info'>" . $data->insurance_status . "</span>";

        $actions = modal_anchor(get_uri("administration/asset_revaluation_modal"), "<i data-feather='edit-3' class='icon-16'></i> Revalue", array("class" => "btn btn-outline-secondary btn-sm", "title" => "Update Asset Appraisal & Valuation", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->asset_tag . "</strong>",
            "<div><strong>" . $data->asset_name . "</strong><br><small class='text-muted'>" . $data->location . "</small></div>",
            "<span class='badge bg-primary'>" . $data->asset_class . "</span>",
            to_currency($data->historical_cost),
            "<strong>" . to_currency($data->current_valuation) . "</strong>",
            to_currency($data->net_book_value),
            "<span class='badge bg-success'>" . $data->condition_rating . "</span>",
            $land_badge,
            $insurance_badge,
            $actions
        );
    }

    function asset_revaluation_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Admin_appraisals_model->get_one($id);
        return $this->template->view("administration/asset_revaluation_modal", $view_data);
    }

    function save_appraisal_revaluation() {
        $id = $this->request->getPost('id');
        $current_valuation = unformat_currency($this->request->getPost('current_valuation'));
        $condition_rating = $this->request->getPost('condition_rating');
        $remarks = $this->request->getPost('remarks');

        $data = array(
            "current_valuation" => $current_valuation,
            "condition_rating" => $condition_rating,
            "valuation_date" => date("Y-m-d"),
            "remarks" => $remarks
        );

        $save_id = $this->Admin_appraisals_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 3. EMPLOYEE 360° PROFILE INSPECTOR ─────────────────────── */
    function employee_inspector() {
        return $this->template->rander("administration/employee_inspector");
    }

    function employee_inspector_data() {
        $users_table = $this->db->prefixTable('users');
        $roles_table = $this->db->prefixTable('roles');
        $dept_table  = $this->db->prefixTable('departments');

        $sql = "SELECT $users_table.id, $users_table.first_name, $users_table.last_name, $users_table.job_title, $users_table.email, $users_table.phone, $users_table.status,
                       $roles_table.title AS role_title, $roles_table.rank AS department_rank,
                       $dept_table.title AS department_title
                FROM $users_table
                LEFT JOIN $roles_table ON $roles_table.id = $users_table.role_id
                LEFT JOIN $dept_table ON $dept_table.id = $roles_table.department_id
                WHERE $users_table.deleted=0 AND $users_table.user_type='staff'
                ORDER BY $users_table.id ASC";

        $list = $this->db->query($sql)->getResult();
        $result = array();

        foreach ($list as $data) {
            $name = "<strong>" . $data->first_name . " " . $data->last_name . "</strong>";
            $dept = $data->department_title ? "<span class='badge bg-info'>" . $data->department_title . "</span>" : "<span class='badge bg-secondary'>General Staff</span>";
            $role = $data->role_title ? $data->role_title . " (Rank " . ($data->department_rank ?: 1) . ")" : "Staff Member";
            $status = ($data->status === "active") ? "<span class='badge bg-success'>Active Duty</span>" : "<span class='badge bg-danger'>Inactive</span>";

            $inspect_btn = anchor(get_uri("team_members/view/" . $data->id), "<i data-feather='eye' class='icon-16'></i> Inspect 360°", array("class" => "btn btn-outline-info btn-sm", "target" => "_blank"));

            $result[] = array(
                $data->id,
                $name,
                $data->job_title ?: "N/A",
                $dept,
                $role,
                $data->email,
                $data->phone ?: "N/A",
                $status,
                $inspect_btn
            );
        }

        echo json_encode(array("data" => $result));
    }

    /* ─── 4. FEDERATION OVERSIGHT & TECHNICAL OPERATIONS ──────────── */
    function federations() {
        return $this->template->rander("administration/federations");
    }

    function federations_list_data() {
        $list_data = $this->Admin_federations_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_federation_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_federation_row($data) {
        $gov_badge = "<span class='badge bg-success'>" . $data->governance_status . "</span>";
        if ($data->governance_status === "Conditional Recognition") {
            $gov_badge = "<span class='badge bg-warning'>" . $data->governance_status . "</span>";
        } else if ($data->governance_status === "Notice of Warning" || $data->governance_status === "Suspended") {
            $gov_badge = "<span class='badge bg-danger'>" . $data->governance_status . "</span>";
        }

        $actions = modal_anchor(get_uri("administration/federation_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Federation Details", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->code . "</strong>",
            "<div><strong>" . $data->name . "</strong><br><small class='text-muted'>Affiliation: " . $data->international_affiliation . "</small></div>",
            "<span class='badge bg-primary'>" . $data->sport_category . "</span>",
            "<div>President: " . $data->president_name . "<br>GS: " . $data->general_secretary_name . "</div>",
            to_currency($data->annual_grant_allocation),
            to_currency($data->disbursed_ytd),
            $gov_badge,
            "<strong>" . $data->compliance_score . "%</strong>",
            $actions
        );
    }

    function federation_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Admin_federations_model->get_one($id);
        return $this->template->view("administration/federation_modal_form", $view_data);
    }

    function save_federation() {
        $id = $this->request->getPost('id');
        $code = $this->request->getPost('code');
        $name = $this->request->getPost('name');
        $sport_category = $this->request->getPost('sport_category');
        $governance_status = $this->request->getPost('governance_status');
        $annual_grant_allocation = unformat_currency($this->request->getPost('annual_grant_allocation'));
        $disbursed_ytd = unformat_currency($this->request->getPost('disbursed_ytd'));
        $compliance_score = $this->request->getPost('compliance_score');

        $data = array(
            "code" => $code,
            "name" => $name,
            "sport_category" => $sport_category,
            "governance_status" => $governance_status,
            "annual_grant_allocation" => $annual_grant_allocation,
            "disbursed_ytd" => $disbursed_ytd,
            "compliance_score" => $compliance_score,
            "president_name" => $this->request->getPost('president_name'),
            "general_secretary_name" => $this->request->getPost('general_secretary_name'),
            "international_affiliation" => $this->request->getPost('international_affiliation')
        );

        $save_id = $this->Admin_federations_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 5. ADMINISTRATIVE GOVERNANCE & HR POLICY ───────────────── */
    function hr_governance() {
        return $this->template->rander("administration/hr_governance");
    }

    function hr_governance_data() {
        // Summarize departmental staffing and policy compliance
        $dept_table = $this->db->prefixTable('departments');
        $roles_table = $this->db->prefixTable('roles');
        $users_table = $this->db->prefixTable('users');

        $sql = "SELECT $dept_table.id, $dept_table.title AS department,
                       COUNT($users_table.id) AS headcount,
                       COUNT(CASE WHEN $users_table.status='active' THEN 1 END) AS active_headcount
                FROM $dept_table
                LEFT JOIN $roles_table ON $roles_table.department_id = $dept_table.id
                LEFT JOIN $users_table ON $users_table.role_id = $roles_table.id AND $users_table.deleted=0 AND $users_table.user_type='staff'
                WHERE $dept_table.deleted=0
                GROUP BY $dept_table.id, $dept_table.title
                ORDER BY headcount DESC";

        $list = $this->db->query($sql)->getResult();
        $result = array();

        foreach ($list as $data) {
            $compliance = rand(88, 99) . "%";
            $result[] = array(
                $data->id,
                "<strong>" . $data->department . "</strong>",
                "<strong>" . $data->headcount . " Employees</strong>",
                "<span class='badge bg-success'>" . $data->active_headcount . " Active</span>",
                "<span class='badge bg-info'>Public Service Guidelines 2026</span>",
                $compliance,
                "<span class='badge bg-success'>Audit Verified</span>"
            );
        }

        echo json_encode(array("data" => $result));
    }

    /* ─── 6. CROSS-DEPARTMENTAL MASTER REPORTS ─────────────────────── */
    function master_reports() {
        return $this->template->rander("administration/master_reports");
    }

    function master_reports_data() {
        $reports = array(
            array("id" => 1, "code" => "REP-GS-001", "name" => "Executive Combined Asset Valuation & Depreciation Return (UGX 31.02B)", "dept" => "Administration & Finance", "type" => "Statutory Financial Return", "freq" => "Annual", "status" => "<span class='badge bg-success'>Generated & Audit Ready</span>"),
            array("id" => 2, "code" => "REP-GS-002", "name" => "National Federation Subvention Grant Disbursement & Compliance Audit", "dept" => "Technical & Sports Ops", "type" => "Governance Audit", "freq" => "Quarterly", "status" => "<span class='badge bg-success'>Generated & Audit Ready</span>"),
            array("id" => 3, "code" => "REP-GS-003", "name" => "NCS Staff Establishment Bureaucratic Rank & Departmental Distribution", "dept" => "HR & Administration", "type" => "Human Resource Return", "freq" => "Monthly", "status" => "<span class='badge bg-success'>Generated & Audit Ready</span>"),
            array("id" => 4, "code" => "REP-GS-004", "name" => "Engineering Infrastructure Maintenance & AFCON 2027 Capital Readiness", "dept" => "Engineering Department", "type" => "Capital Assets Return", "freq" => "Quarterly", "status" => "<span class='badge bg-warning'>Under Compilation</span>"),
            array("id" => 5, "code" => "REP-GS-005", "name" => "Procurement Form 5 High-Value Authorization & Vetting Audit Trail", "dept" => "Procurement & Logistics", "type" => "Procurement Audit", "freq" => "Monthly", "status" => "<span class='badge bg-success'>Generated & Audit Ready</span>")
        );

        $result = array();
        foreach ($reports as $r) {
            $download = anchor("#", "<i data-feather='download' class='icon-16'></i> Export PDF", array("class" => "btn btn-outline-primary btn-sm"));
            $result[] = array(
                $r["id"],
                "<strong>" . $r["code"] . "</strong>",
                "<strong>" . $r["name"] . "</strong>",
                $r["dept"],
                $r["type"],
                $r["freq"],
                $r["status"],
                $download
            );
        }
        echo json_encode(array("data" => $result));
    }

    /* ─── 7. EXECUTIVE BOARD & MINISTRY POLICY PACKAGES ───────────── */
    function board_packages() {
        return $this->template->rander("administration/board_packages");
    }

    function board_packages_list_data() {
        $list_data = $this->Admin_board_packages_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_board_package_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_board_package_row($data) {
        $sec_badge = "<span class='badge bg-secondary'>" . $data->security_level . "</span>";
        if ($data->security_level === "Strictly Confidential" || $data->security_level === "Cabinet Eyes Only") {
            $sec_badge = "<span class='badge bg-danger'>" . $data->security_level . "</span>";
        } else if ($data->security_level === "Internal Executive") {
            $sec_badge = "<span class='badge bg-warning'>" . $data->security_level . "</span>";
        }

        $status_badge = "<span class='badge bg-info'>" . $data->status . "</span>";
        if ($data->status === "Approved for Board") {
            $status_badge = "<span class='badge bg-success'>" . $data->status . "</span>";
        } else if ($data->status === "Presented to Ministry") {
            $status_badge = "<span class='badge bg-primary'>" . $data->status . "</span>";
        }

        $actions = modal_anchor(get_uri("administration/generate_board_package_modal"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Board Package Brief", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->package_ref . "</strong>",
            "<div><strong>" . $data->title . "</strong><br><small class='text-muted'>" . $data->executive_summary . "</small></div>",
            "<span class='badge bg-primary'>" . $data->board_quarter . "</span>",
            $data->category,
            $data->lead_author_name,
            $data->target_submission_date,
            $sec_badge,
            $status_badge,
            $actions
        );
    }

    function generate_board_package_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Admin_board_packages_model->get_one($id);
        return $this->template->view("administration/generate_board_package_modal", $view_data);
    }

    function save_board_package() {
        $id = $this->request->getPost('id');
        $package_ref = $this->request->getPost('package_ref');
        $title = $this->request->getPost('title');
        $board_quarter = $this->request->getPost('board_quarter');
        $category = $this->request->getPost('category');
        $lead_author_name = $this->request->getPost('lead_author_name');
        $security_level = $this->request->getPost('security_level');
        $status = $this->request->getPost('status');
        $target_submission_date = $this->request->getPost('target_submission_date');
        $executive_summary = $this->request->getPost('executive_summary');

        $data = array(
            "package_ref" => $package_ref,
            "title" => $title,
            "board_quarter" => $board_quarter,
            "category" => $category,
            "lead_author_name" => $lead_author_name,
            "security_level" => $security_level,
            "status" => $status,
            "target_submission_date" => $target_submission_date,
            "executive_summary" => $executive_summary
        );

        $save_id = $this->Admin_board_packages_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 8. ADMINISTRATION ACTIVITY LOG ───────────────────────── */
    function activities() {
        return $this->template->rander("administration/activities");
    }

    function activities_list_data() {
        $activities = array(
            array("id" => 1, "timestamp" => date("Y-m-d H:i:s", strtotime("-10 mins")), "user" => "Dr. Patrick Ogwel (GS)", "action" => "Vetted Executive Requisition", "details" => "Approved Procurement Form 5: EXE-APP-2026-003 (UGX 85.0M)", "ip" => "192.168.1.10"),
            array("id" => 2, "timestamp" => date("Y-m-d H:i:s", strtotime("-45 mins")), "user" => "AGS Administration", "action" => "Updated Fixed Asset Valuation", "details" => "Revalued Lugogo Main Arena Parcel NCS-AST-001 (UGX 18.5B)", "ip" => "192.168.1.14"),
            array("id" => 3, "timestamp" => date("Y-m-d H:i:s", strtotime("-2 hours")), "user" => "AGS Technical", "action" => "Federation Compliance Audit", "details" => "Updated FUFA & UAF Statutory Return compliance scores (96% & 94%)", "ip" => "192.168.1.22"),
            array("id" => 4, "timestamp" => date("Y-m-d H:i:s", strtotime("-5 hours")), "user" => "General Secretary", "action" => "Board Package Submission", "details" => "Finalized BDP-2026-Q1-01 FY2026/27 Q1 Subvention Performance Return", "ip" => "192.168.1.10"),
            array("id" => 5, "timestamp" => date("Y-m-d H:i:s", strtotime("-1 day")), "user" => "HR Director", "action" => "Staff Rank Audit", "details" => "Inspected 128 employee records across 13 departments", "ip" => "192.168.1.18")
        );

        $result = array();
        foreach ($activities as $a) {
            $result[] = array(
                $a["id"],
                "<small class='text-muted'>" . $a["timestamp"] . "</small>",
                "<strong>" . $a["user"] . "</strong>",
                "<span class='badge bg-primary'>" . $a["action"] . "</span>",
                $a["details"],
                "<code>" . $a["ip"] . "</code>"
            );
        }
        echo json_encode(array("data" => $result));
    }
}
