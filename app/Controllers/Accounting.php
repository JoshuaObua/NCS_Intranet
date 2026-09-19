<?php

namespace App\Controllers;

class Accounting extends Security_Controller {

    protected $db;
    public $Accounting_ledgers_model;
    public $Accounting_grants_model;
    public $Accounting_vote_clearance_model;
    public $Accounting_reconciliations_model;
    public $Admin_appraisals_model;

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
        $this->db = \Config\Database::connect();
        $this->Accounting_ledgers_model = model("App\Models\Accounting_ledgers_model");
        $this->Accounting_grants_model = model("App\Models\Accounting_grants_model");
        $this->Accounting_vote_clearance_model = model("App\Models\Accounting_vote_clearance_model");
        $this->Accounting_reconciliations_model = model("App\Models\Accounting_reconciliations_model");
        $this->Admin_appraisals_model = model("App\Models\Admin_appraisals_model");
    }

    function index() {
        return $this->ledger();
    }

    /* ─── 1. GENERAL LEDGER & JOURNAL ENTRIES ─────────────────────── */
    function ledger() {
        return $this->template->rander("accounting/index");
    }

    function ledger_list_data() {
        $list_data = $this->Accounting_ledgers_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_ledger_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_ledger_row($data) {
        $status_badge = "<span class='badge bg-success'>" . $data->status . "</span>";
        if ($data->status === "DRAFT") {
            $status_badge = "<span class='badge bg-warning'>" . $data->status . "</span>";
        } else if ($data->status === "REVERSED") {
            $status_badge = "<span class='badge bg-danger'>" . $data->status . "</span>";
        }

        $actions = modal_anchor(get_uri("accounting/journal_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Journal Voucher", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->voucher_number . "</strong>",
            $data->posting_date,
            "<code>" . $data->vote_head_code . "</code> - " . $data->vote_head_name,
            $data->description,
            to_currency($data->debit_amount),
            to_currency($data->credit_amount),
            $data->poster_name,
            $status_badge,
            $actions
        );
    }

    function journal_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Accounting_ledgers_model->get_one($id);
        return $this->template->view("accounting/journal_modal_form", $view_data);
    }

    function save_journal() {
        $id = $this->request->getPost('id');
        $data = array(
            "voucher_number" => $this->request->getPost('voucher_number'),
            "posting_date" => $this->request->getPost('posting_date'),
            "vote_head_code" => $this->request->getPost('vote_head_code'),
            "vote_head_name" => $this->request->getPost('vote_head_name'),
            "description" => $this->request->getPost('description'),
            "debit_amount" => unformat_currency($this->request->getPost('debit_amount')),
            "credit_amount" => unformat_currency($this->request->getPost('credit_amount')),
            "status" => $this->request->getPost('status'),
            "poster_name" => $this->login_user->first_name . " " . $this->login_user->last_name
        );

        $save_id = $this->Accounting_ledgers_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 2. FIXED ASSETS & DEPRECIATION REGISTER (UGX 32.18B) ───── */
    function assets() {
        return $this->template->rander("accounting/assets");
    }

    function assets_list_data() {
        $list_data = $this->Admin_appraisals_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_asset_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_asset_row($data) {
        $actions = modal_anchor(get_uri("accounting/asset_revaluation_modal"), "<i data-feather='edit-3' class='icon-16'></i> Revalue / Depreciate", array("class" => "btn btn-outline-secondary btn-sm", "title" => "Post IPSAS 17 Revaluation & Depreciation", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->asset_tag . "</strong>",
            "<div><strong>" . $data->asset_name . "</strong><br><small class='text-muted'>" . $data->location . "</small></div>",
            "<span class='badge bg-primary'>" . $data->asset_class . "</span>",
            to_currency($data->historical_cost),
            "<strong>" . to_currency($data->current_valuation) . "</strong>",
            to_currency($data->accumulated_depreciation),
            "<strong>" . to_currency($data->net_book_value) . "</strong>",
            "<span class='badge bg-success'>" . $data->condition_rating . "</span>",
            $actions
        );
    }

    function asset_revaluation_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Admin_appraisals_model->get_one($id);
        return $this->template->view("accounting/asset_revaluation_modal", $view_data);
    }

    function save_asset_revaluation() {
        $id = $this->request->getPost('id');
        $current_valuation = unformat_currency($this->request->getPost('current_valuation'));
        $accumulated_depreciation = unformat_currency($this->request->getPost('accumulated_depreciation'));
        $net_book_value = $current_valuation - $accumulated_depreciation;

        $data = array(
            "current_valuation" => $current_valuation,
            "accumulated_depreciation" => $accumulated_depreciation,
            "net_book_value" => $net_book_value,
            "valuation_date" => date("Y-m-d"),
            "remarks" => $this->request->getPost('remarks')
        );

        $save_id = $this->Admin_appraisals_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 3. FEDERATION SUBVENTIONS & GRANTS ─────────────────────── */
    function grants() {
        return $this->template->rander("accounting/grants");
    }

    function grants_list_data() {
        $list_data = $this->Accounting_grants_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_grant_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_grant_row($data) {
        $status_badge = "<span class='badge bg-success'>" . $data->accountability_status . "</span>";
        if ($data->accountability_status === "PENDING_AUDIT") {
            $status_badge = "<span class='badge bg-warning'>PENDING AUDIT</span>";
        } else if ($data->accountability_status === "OVERDUE") {
            $status_badge = "<span class='badge bg-danger'>OVERDUE</span>";
        }

        $actions = modal_anchor(get_uri("accounting/grant_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Federation Subvention", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->federation_code . "</strong>",
            "<div><strong>" . $data->federation_name . "</strong><br><small class='text-muted'>" . $data->notes . "</small></div>",
            "<span class='badge bg-primary'>" . $data->grant_quarter . "</span>",
            to_currency($data->allocated_amount),
            "<strong>" . to_currency($data->disbursed_amount) . "</strong>",
            $data->disbursement_date,
            $status_badge,
            $actions
        );
    }

    function grant_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Accounting_grants_model->get_one($id);
        return $this->template->view("accounting/grant_modal_form", $view_data);
    }

    function save_grant_disbursement() {
        $id = $this->request->getPost('id');
        $data = array(
            "federation_code" => $this->request->getPost('federation_code'),
            "federation_name" => $this->request->getPost('federation_name'),
            "grant_quarter" => $this->request->getPost('grant_quarter'),
            "allocated_amount" => unformat_currency($this->request->getPost('allocated_amount')),
            "disbursed_amount" => unformat_currency($this->request->getPost('disbursed_amount')),
            "accountability_status" => $this->request->getPost('accountability_status'),
            "notes" => $this->request->getPost('notes')
        );

        $save_id = $this->Accounting_grants_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 4. PPDA FORM 5 VOTE CLEARANCE ───────────────────────────── */
    function vote_clearance() {
        return $this->template->rander("accounting/vote_clearance");
    }

    function vote_clearance_list_data() {
        $list_data = $this->Accounting_vote_clearance_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_vote_clearance_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_vote_clearance_row($data) {
        $status_badge = "<span class='badge bg-success'>" . $data->clearance_status . "</span>";
        if ($data->clearance_status === "HELD") {
            $status_badge = "<span class='badge bg-warning'>" . $data->clearance_status . "</span>";
        } else if ($data->clearance_status === "REJECTED") {
            $status_badge = "<span class='badge bg-danger'>" . $data->clearance_status . "</span>";
        }

        $actions = modal_anchor(get_uri("accounting/vote_clearance_modal"), "<i data-feather='check-square' class='icon-16'></i> Clear Vote", array("class" => "btn btn-outline-primary btn-sm", "title" => "Execute Vote-Head Budget Clearance", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->requisition_ref . "</strong>",
            "<span class='badge bg-info'>" . $data->requesting_department . "</span>",
            "<code>" . $data->vote_head_code . "</code> - " . $data->vote_head_title,
            to_currency($data->requested_amount),
            "<strong>" . to_currency($data->available_budget) . "</strong>",
            $data->clearance_officer,
            $status_badge,
            $actions
        );
    }

    function vote_clearance_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Accounting_vote_clearance_model->get_one($id);
        return $this->template->view("accounting/vote_clearance_modal", $view_data);
    }

    function save_vote_clearance() {
        $id = $this->request->getPost('id');
        $data = array(
            "clearance_status" => $this->request->getPost('clearance_status'),
            "remarks" => $this->request->getPost('remarks'),
            "clearance_officer" => $this->login_user->first_name . " " . $this->login_user->last_name,
            "clearance_date" => date("Y-m-d")
        );

        $save_id = $this->Accounting_vote_clearance_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 5. NTR & BANK RECONCILIATION ───────────────────────────── */
    function reconciliation() {
        return $this->template->rander("accounting/reconciliation");
    }

    function reconciliation_list_data() {
        $list_data = $this->Accounting_reconciliations_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_reconciliation_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_reconciliation_row($data) {
        $status_badge = "<span class='badge bg-success'>" . $data->status . "</span>";
        if ($data->variance != 0) {
            $status_badge = "<span class='badge bg-danger'>VARIANCE DISCREPANCY</span>";
        }

        $actions = modal_anchor(get_uri("accounting/reconciliation_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Bank Reconciliation Statement", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->reconciliation_ref . "</strong>",
            "<div><strong>" . $data->bank_account_name . "</strong><br><small class='text-muted'>" . $data->bank_account_number . "</small></div>",
            $data->statement_date,
            to_currency($data->system_balance),
            to_currency($data->bank_statement_balance),
            "<strong>" . to_currency($data->variance) . "</strong>",
            $data->reconciled_by,
            $status_badge,
            $actions
        );
    }

    function reconciliation_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Accounting_reconciliations_model->get_one($id);
        return $this->template->view("accounting/reconciliation_modal_form", $view_data);
    }

    function save_reconciliation() {
        $id = $this->request->getPost('id');
        $system_bal = unformat_currency($this->request->getPost('system_balance'));
        $bank_bal = unformat_currency($this->request->getPost('bank_statement_balance'));
        $variance = $system_bal - $bank_bal;

        $data = array(
            "reconciliation_ref" => $this->request->getPost('reconciliation_ref'),
            "bank_account_name" => $this->request->getPost('bank_account_name'),
            "bank_account_number" => $this->request->getPost('bank_account_number'),
            "statement_date" => $this->request->getPost('statement_date'),
            "system_balance" => $system_bal,
            "bank_statement_balance" => $bank_bal,
            "variance" => $variance,
            "status" => ($variance == 0) ? "RECONCILED" : "DISCREPANCY",
            "reconciled_by" => $this->login_user->first_name . " " . $this->login_user->last_name
        );

        $save_id = $this->Accounting_reconciliations_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 6. BUDGET EXECUTION & VOTE-HEADS PERFORMANCE ───────────── */
    function budget() {
        return $this->template->rander("accounting/budget");
    }

    function budget_list_data() {
        $vote_heads = array(
            array("id" => 1, "code" => "Vote 211101", "name" => "General Staff Salaries", "approved" => 4110000000.00, "committed" => 3082500000.00, "balance" => 1027500000.00, "pct" => "75.0%"),
            array("id" => 2, "code" => "Vote 221002", "name" => "Workshops, Seminars & Subventions", "approved" => 18000000000.00, "committed" => 13500000000.00, "balance" => 4500000000.00, "pct" => "75.0%"),
            array("id" => 3, "code" => "Vote 311101", "name" => "Fixed Infrastructure CapEx Maintenance", "approved" => 5500000000.00, "committed" => 4125000000.00, "balance" => 1375000000.00, "pct" => "75.0%"),
            array("id" => 4, "code" => "Vote 227001", "name" => "Travel & Transport Allowances", "approved" => 850000000.00, "committed" => 637500000.00, "balance" => 212500000.00, "pct" => "75.0%"),
            array("id" => 5, "code" => "Vote 221008", "name" => "IT Consumables & Systems Security", "approved" => 350000000.00, "committed" => 262500000.00, "balance" => 87500000.00, "pct" => "75.0%")
        );

        $result = array();
        foreach ($vote_heads as $v) {
            $result[] = array(
                $v["id"],
                "<code>" . $v["code"] . "</code>",
                "<strong>" . $v["name"] . "</strong>",
                to_currency($v["approved"]),
                to_currency($v["committed"]),
                "<strong>" . to_currency($v["balance"]) . "</strong>",
                "<span class='badge bg-info'>" . $v["pct"] . " Committed</span>"
            );
        }
        echo json_encode(array("data" => $result));
    }

    /* ─── 7. INTERNAL AUDIT & SPOT-CHECK VERIFICATION ────────────── */
    function audit_verification() {
        return $this->template->rander("accounting/audit_verification");
    }

    function audit_verification_data() {
        $audits = array(
            array("id" => 1, "ref" => "AUD-2026-001", "scope" => "Fixed Asset Register Barcode Verification (297 Items)", "auditor" => "Internal Audit HOD", "status" => "<span class='badge bg-success'>100% Tagged & Clean</span>", "date" => "2026-09-08"),
            array("id" => 2, "ref" => "AUD-2026-002", "scope" => "National Federation Subvention Accountability Spot-Check", "auditor" => "Senior Internal Auditor", "status" => "<span class='badge bg-success'>FUFA & UAF Cleared</span>", "date" => "2026-09-12"),
            array("id" => 3, "ref" => "AUD-2026-003", "scope" => "Procurement Form 5 Vote-Head Budget Overspend Audit", "auditor" => "Audit Verifier", "status" => "<span class='badge bg-success'>No Overspend Discrepancy</span>", "date" => "2026-09-15"),
            array("id" => 4, "ref" => "AUD-2026-004", "scope" => "Bank Statement vs Intranet NTR Collections Reconciliation Audit", "auditor" => "Internal Audit HOD", "status" => "<span class='badge bg-success'>Zero Variance Cleared</span>", "date" => "2026-09-16")
        );

        $result = array();
        foreach ($audits as $a) {
            $result[] = array(
                $a["id"],
                "<strong>" . $a["ref"] . "</strong>",
                "<strong>" . $a["scope"] . "</strong>",
                $a["auditor"],
                $a["date"],
                $a["status"],
                anchor("#", "<i data-feather='file-text' class='icon-16'></i> View Audit File", array("class" => "btn btn-outline-info btn-sm"))
            );
        }
        echo json_encode(array("data" => $result));
    }

    /* ─── 8. FINANCIAL REPORTS & STATUTORY STATEMENTS ─────────────── */
    function reports() {
        return $this->template->rander("accounting/reports");
    }

    function reports_data() {
        $reports = array(
            array("id" => 1, "code" => "FIN-REP-001", "name" => "Statement of Financial Position (Balance Sheet - Fixed Assets UGX 32.18B)", "dept" => "Finance & Accounts", "std" => "IPSAS 17", "freq" => "Annual", "status" => "<span class='badge bg-success'>Audit Ready</span>"),
            array("id" => 2, "code" => "FIN-REP-002", "name" => "Statement of Budget Performance & Vote-Head Commitment Return", "dept" => "Finance & Accounts", "std" => "PFMA 2015", "freq" => "Quarterly", "status" => "<span class='badge bg-success'>Audit Ready</span>"),
            array("id" => 3, "code" => "FIN-REP-003", "name" => "National Sports Federations Grants Disbursement & Accountability Return", "dept" => "Finance & Accounts", "std" => "Treasury Instructions", "freq" => "Quarterly", "status" => "<span class='badge bg-success'>Audit Ready</span>"),
            array("id" => 4, "code" => "FIN-REP-004", "name" => "Non-Tax Revenue (NTR) Collection & Bank Reconciliation Disclosure", "dept" => "Finance & Accounts", "std" => "MoFPED NTR", "freq" => "Monthly", "status" => "<span class='badge bg-success'>Audit Ready</span>")
        );

        $result = array();
        foreach ($reports as $r) {
            $download = anchor("#", "<i data-feather='download' class='icon-16'></i> Export PDF", array("class" => "btn btn-outline-primary btn-sm"));
            $result[] = array(
                $r["id"],
                "<strong>" . $r["code"] . "</strong>",
                "<strong>" . $r["name"] . "</strong>",
                $r["dept"],
                "<span class='badge bg-primary'>" . $r["std"] . "</span>",
                $r["freq"],
                $r["status"],
                $download
            );
        }
        echo json_encode(array("data" => $result));
    }
}
