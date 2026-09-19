<?php

namespace App\Controllers;

class Internal_audit extends Security_Controller {

    protected $db;
    public $Audit_discrepancies_model;
    public $Audit_reports_model;
    public $Fixed_assets_model;

    function __construct() {
        parent::__construct();
        helper(['currency', 'general', 'custom', 'plugin']);
        $this->access_only_team_members();
        $this->db = \Config\Database::connect();
        $this->Audit_discrepancies_model = model("App\Models\Audit_discrepancies_model");
        $this->Audit_reports_model = model("App\Models\Audit_reports_model");
        $this->Fixed_assets_model = model("App\Models\Fixed_assets_model");
    }

    function index() {
        return $this->dashboard();
    }

    /* ─── 1. INTERNAL AUDIT DASHBOARD & CONTROLS ─────────────────── */
    function dashboard() {
        $view_data['summary'] = $this->Audit_discrepancies_model->get_audit_summary();
        $view_data['asset_totals'] = $this->Fixed_assets_model->get_portfolio_totals();
        return $this->template->rander("internal_audit/index", $view_data);
    }

    /* ─── 2. PHYSICAL TAG SPOT-CHECKS & QR SCANNER ─────────────────── */
    function spot_checks() {
        $view_data['asset_totals'] = $this->Fixed_assets_model->get_portfolio_totals();
        return $this->template->rander("internal_audit/spot_checks", $view_data);
    }

    function spot_check_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Fixed_assets_model->get_one($id);
        return $this->template->view("internal_audit/spot_check_modal", $view_data);
    }

    function modal_spot_check_form() {
        return $this->spot_check_modal_form();
    }

    /* ─── 3. AUDIT DISCREPANCY & EXCEPTION MANAGER ─────────────────── */
    function discrepancies() {
        $view_data['summary'] = $this->Audit_discrepancies_model->get_audit_summary();
        return $this->template->rander("internal_audit/discrepancies", $view_data);
    }

    function discrepancies_list_data() {
        $list_data = $this->Audit_discrepancies_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_discrepancy_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_discrepancy_row($data) {
        $severity_badge = "<span class='badge bg-info'>" . $data->severity . "</span>";
        if ($data->severity === "HIGH") {
            $severity_badge = "<span class='badge bg-warning'>HIGH</span>";
        } else if ($data->severity === "CRITICAL") {
            $severity_badge = "<span class='badge bg-danger'>CRITICAL</span>";
        }

        $status_badge = "<span class='badge bg-warning'>" . $data->status . "</span>";
        if ($data->status === "RESOLVED") {
            $status_badge = "<span class='badge bg-success'>RESOLVED</span>";
        } else if ($data->status === "ESCALATED") {
            $status_badge = "<span class='badge bg-danger'>ESCALATED TO GS</span>";
        }

        $actions = modal_anchor(get_uri("internal_audit/modal_discrepancy_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Audit Discrepancy", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->discrepancy_code . "</strong>",
            "<span class='badge bg-primary'>" . $data->entity_type . "</span>",
            "<code>" . $data->entity_ref . "</code>",
            "<div><strong>" . $data->title . "</strong><br><small class='text-muted'>" . $data->description . "</small></div>",
            $severity_badge,
            "<strong>" . to_currency($data->financial_impact_ugx) . "</strong>",
            $status_badge,
            $actions
        );
    }

    function discrepancy_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Audit_discrepancies_model->get_one($id);
        return $this->template->view("internal_audit/discrepancy_modal_form", $view_data);
    }

    function modal_discrepancy_form() {
        return $this->discrepancy_modal_form();
    }

    function save_discrepancy() {
        $id = $this->request->getPost('id');
        $data = array(
            "discrepancy_code" => $this->request->getPost('discrepancy_code') ?: "AUD-DISC-2026-00" . rand(4,9),
            "entity_type" => $this->request->getPost('entity_type'),
            "entity_ref" => $this->request->getPost('entity_ref'),
            "severity" => $this->request->getPost('severity'),
            "title" => $this->request->getPost('title'),
            "description" => $this->request->getPost('description'),
            "financial_impact_ugx" => unformat_currency($this->request->getPost('financial_impact_ugx')),
            "status" => $this->request->getPost('status') ?: 'OPEN',
            "management_response" => $this->request->getPost('management_response'),
            "raised_by" => $this->login_user->id
        );

        $save_id = $this->Audit_discrepancies_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 4. FINANCIAL CONTROL & LEDGER VERIFICATION ────────────────── */
    function financial_controls() {
        return $this->template->rander("internal_audit/financial_controls");
    }

    /* ─── 5. STATUTORY AUDIT MATRIX ─────────────────────────────────── */
    function compliance_matrix() {
        return $this->template->rander("internal_audit/compliance_matrix");
    }

    /* ─── 6. UGANDAN FORMAT INTERNAL AUDIT REPORT GENERATOR ────────── */
    function generate_report() {
        $view_data['report'] = $this->Audit_reports_model->get_one(1);
        $view_data['discrepancies'] = $this->Audit_discrepancies_model->get_details()->getResult();
        $view_data['asset_totals'] = $this->Fixed_assets_model->get_portfolio_totals();
        return $this->template->rander("internal_audit/generate_report", $view_data);
    }

    function save_report() {
        $id = $this->request->getPost('id');
        $data = array(
            "report_code" => $this->request->getPost('report_code') ?: "AUD-REP-2026-Q1",
            "report_title" => $this->request->getPost('report_title'),
            "financial_year" => $this->request->getPost('financial_year') ?: "FY 2026/2027",
            "quarter" => $this->request->getPost('quarter') ?: "Q1",
            "audit_period" => $this->request->getPost('audit_period') ?: "Period Ended 30th September 2026",
            "overall_opinion" => $this->request->getPost('overall_opinion') ?: "SATISFACTORY",
            "summary_findings" => $this->request->getPost('summary_findings'),
            "compiled_by" => $this->login_user->id
        );

        $save_id = $this->Audit_reports_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => "Internal Audit Report saved successfully."));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 7. AUDIT ACTIVITY LOG & TRAIL ────────────────────────────── */
    function activity_log() {
        return $this->template->rander("internal_audit/activity_log");
    }
}
