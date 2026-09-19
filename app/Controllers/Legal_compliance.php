<?php

namespace App\Controllers;

class Legal_compliance extends Security_Controller {

    protected $db;
    public $Legal_contracts_model;
    public $Legal_disputes_model;
    public $Legal_trademarks_model;
    public $Legal_litigation_model;

    function __construct() {
        parent::__construct();
        helper(['currency', 'general', 'custom', 'plugin']);
        $this->access_only_team_members();
        $this->db = \Config\Database::connect();
        $this->Legal_contracts_model = model("App\Models\Legal_contracts_model");
        $this->Legal_disputes_model = model("App\Models\Legal_disputes_model");
        $this->Legal_trademarks_model = model("App\Models\Legal_trademarks_model");
        $this->Legal_litigation_model = model("App\Models\Legal_litigation_model");
    }

    function index() {
        return $this->contracts();
    }

    /* ─── 1. LEGAL CONTRACTS & MOUS VAULT ─────────────────────────── */
    function contracts() {
        return $this->template->rander("legal_compliance/index");
    }

    function contracts_list_data() {
        $list_data = $this->Legal_contracts_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_contract_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_contract_row($data) {
        $status_badge = "<span class='badge bg-success'>" . $data->status . "</span>";
        if ($data->status === "UNDER_RENEWAL") {
            $status_badge = "<span class='badge bg-warning'>UNDER RENEWAL</span>";
        } else if ($data->status === "EXPIRED" || $data->status === "IN_DISPUTE") {
            $status_badge = "<span class='badge bg-danger'>" . $data->status . "</span>";
        }

        $actions = modal_anchor(get_uri("legal_compliance/contract_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Legal Contract", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->contract_reference . "</strong>",
            "<div><strong>" . $data->title . "</strong><br><small class='text-muted'>" . $data->document_summary . "</small></div>",
            "<span class='badge bg-primary'>" . $data->contract_type . "</span>",
            $data->second_party,
            to_currency($data->contract_value_ugx),
            "<small class='text-muted'>" . $data->start_date . " to " . $data->expiry_date . "</small>",
            $data->legal_officer_name,
            $status_badge,
            $actions
        );
    }

    function contract_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Legal_contracts_model->get_one($id);
        return $this->template->view("legal_compliance/contract_modal_form", $view_data);
    }

    function modal_contract_form() {
        return $this->contract_modal_form();
    }

    function save_contract() {
        $id = $this->request->getPost('id');
        $data = array(
            "contract_reference" => $this->request->getPost('contract_reference'),
            "title" => $this->request->getPost('title'),
            "contract_type" => $this->request->getPost('contract_type'),
            "second_party" => $this->request->getPost('second_party'),
            "contract_value_ugx" => unformat_currency($this->request->getPost('contract_value_ugx')),
            "start_date" => $this->request->getPost('start_date'),
            "expiry_date" => $this->request->getPost('expiry_date'),
            "renewal_notice_days" => $this->request->getPost('renewal_notice_days'),
            "status" => $this->request->getPost('status'),
            "document_summary" => $this->request->getPost('document_summary')
        );

        $save_id = $this->Legal_contracts_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 2. FEDERATION GOVERNANCE & ARBITRATION ─────────────────── */
    function disputes() {
        return $this->template->rander("legal_compliance/disputes");
    }

    function disputes_list_data() {
        $list_data = $this->Legal_disputes_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_dispute_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_dispute_row($data) {
        $status_badge = "<span class='badge bg-warning'>" . $data->case_status . "</span>";
        if ($data->case_status === "CONCLUDED") {
            $status_badge = "<span class='badge bg-success'>" . $data->case_status . "</span>";
        } else if ($data->case_status === "DISMISSED") {
            $status_badge = "<span class='badge bg-secondary'>" . $data->case_status . "</span>";
        }

        $actions = modal_anchor(get_uri("legal_compliance/dispute_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Arbitral Dispute", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->case_number . "</strong>",
            "<span class='badge bg-primary'>" . $data->federation_name . "</span>",
            "<div><strong>" . $data->subject_matter . "</strong><br><small class='text-muted'>" . $data->complainant_name . " vs " . $data->respondent_name . "</small></div>",
            "<span class='badge bg-secondary'>" . $data->dispute_category . "</span>",
            $data->filing_date,
            $data->tribunal_chair_name,
            $status_badge,
            $actions
        );
    }

    function dispute_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Legal_disputes_model->get_one($id);
        return $this->template->view("legal_compliance/dispute_modal_form", $view_data);
    }

    function modal_dispute_form() {
        return $this->dispute_modal_form();
    }

    function save_dispute() {
        $id = $this->request->getPost('id');
        $data = array(
            "case_number" => $this->request->getPost('case_number'),
            "federation_name" => $this->request->getPost('federation_name'),
            "complainant_name" => $this->request->getPost('complainant_name'),
            "respondent_name" => $this->request->getPost('respondent_name'),
            "subject_matter" => $this->request->getPost('subject_matter'),
            "dispute_category" => $this->request->getPost('dispute_category'),
            "filing_date" => $this->request->getPost('filing_date'),
            "tribunal_chair_name" => $this->request->getPost('tribunal_chair_name'),
            "case_status" => $this->request->getPost('case_status'),
            "ruling_summary" => $this->request->getPost('ruling_summary')
        );

        $save_id = $this->Legal_disputes_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 3. STATUTORY & REGULATORY MONITOR ─────────────────────── */
    function statutory() {
        return $this->template->rander("legal_compliance/statutory");
    }

    function statutory_compliance_data() {
        $statutes = array(
            array("id" => 1, "act" => "Uganda National Sports Act (2023)", "scope" => "Registration & Regulation of National Sports Associations", "dept" => "All 51 Sports Federations", "status" => "<span class='badge bg-success'>96.2% Compliant</span>", "date" => "2026-06-30"),
            array("id" => 2, "act" => "Public Finance Management Act (PFMA 2015)", "scope" => "Subvention Expenditure & Financial Accountability", "dept" => "Finance & Accounts / GS", "status" => "<span class='badge bg-success'>100% Compliant</span>", "date" => "2026-08-31"),
            array("id" => 3, "act" => "Public Procurement & Disposal Assets Act (PPDA 2003)", "scope" => "High-Value Procurement Vetting (> UGX 50M)", "dept" => "Procurement & Logistics", "status" => "<span class='badge bg-success'>100% Compliant</span>", "date" => "2026-09-01"),
            array("id" => 4, "act" => "Contracts Act (2010) & Arbitration Act", "scope" => "Commercial Agreements & Land Leases Validation", "dept" => "Legal & Corporate Affairs", "status" => "<span class='badge bg-success'>100% Compliant</span>", "date" => "2026-09-10")
        );

        $result = array();
        foreach ($statutes as $s) {
            $result[] = array(
                $s["id"],
                "<strong>" . $s["act"] . "</strong>",
                $s["scope"],
                "<span class='badge bg-info'>" . $s["dept"] . "</span>",
                $s["date"],
                $s["status"],
                anchor("#", "<i data-feather='eye' class='icon-16'></i> View Gazette", array("class" => "btn btn-outline-info btn-sm"))
            );
        }
        echo json_encode(array("data" => $result));
    }

    /* ─── 4. IP & TRADEMARK PROTECTION ──────────────────────────── */
    function trademarks() {
        return $this->template->rander("legal_compliance/trademarks");
    }

    function trademarks_list_data() {
        $list_data = $this->Legal_trademarks_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_trademark_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_trademark_row($data) {
        $actions = modal_anchor(get_uri("legal_compliance/trademark_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Registered Trademark", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->trademark_code . "</strong>",
            "<div><strong>" . $data->mark_name . "</strong><br><small class='text-muted'>" . $data->notes . "</small></div>",
            "<span class='badge bg-primary'>" . $data->category . "</span>",
            "<code>" . $data->registration_number . "</code>",
            $data->registration_date,
            $data->expiry_date,
            "<span class='badge bg-success'>" . $data->protection_status . "</span>",
            $actions
        );
    }

    function trademark_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Legal_trademarks_model->get_one($id);
        return $this->template->view("legal_compliance/trademark_modal_form", $view_data);
    }

    function modal_trademark_form() {
        return $this->trademark_modal_form();
    }

    function save_trademark() {
        $id = $this->request->getPost('id');
        $data = array(
            "trademark_code" => $this->request->getPost('trademark_code'),
            "mark_name" => $this->request->getPost('mark_name'),
            "category" => $this->request->getPost('category'),
            "registration_number" => $this->request->getPost('registration_number'),
            "registration_date" => $this->request->getPost('registration_date'),
            "expiry_date" => $this->request->getPost('expiry_date'),
            "protection_status" => $this->request->getPost('protection_status'),
            "notes" => $this->request->getPost('notes')
        );

        $save_id = $this->Legal_trademarks_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 5. LITIGATION & COURT PROCEEDINGS ─────────────────────── */
    function litigation() {
        return $this->template->rander("legal_compliance/litigation");
    }

    function litigation_list_data() {
        $list_data = $this->Legal_litigation_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_litigation_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_litigation_row($data) {
        $actions = modal_anchor(get_uri("legal_compliance/litigation_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Litigation Proceeding", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->suit_number . "</strong>",
            "<div><strong>" . $data->plaintiff . " vs " . $data->defendant . "</strong><br><small class='text-muted'>" . $data->case_summary . "</small></div>",
            "<span class='badge bg-info'>" . $data->court_level . "</span>",
            "<strong>" . to_currency($data->legal_exposure_ugx) . "</strong>",
            $data->lead_counsel,
            "<span class='badge bg-warning'>" . $data->case_status . "</span>",
            $actions
        );
    }

    function litigation_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Legal_litigation_model->get_one($id);
        return $this->template->view("legal_compliance/litigation_modal_form", $view_data);
    }

    function modal_litigation_form() {
        return $this->litigation_modal_form();
    }

    function save_litigation() {
        $id = $this->request->getPost('id');
        $data = array(
            "suit_number" => $this->request->getPost('suit_number'),
            "plaintiff" => $this->request->getPost('plaintiff'),
            "defendant" => $this->request->getPost('defendant'),
            "court_level" => $this->request->getPost('court_level'),
            "legal_exposure_ugx" => unformat_currency($this->request->getPost('legal_exposure_ugx')),
            "lead_counsel" => $this->request->getPost('lead_counsel'),
            "case_status" => $this->request->getPost('case_status'),
            "case_summary" => $this->request->getPost('case_summary')
        );

        $save_id = $this->Legal_litigation_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 6. BOARD RESOLUTIONS & SECRETARIAT ─────────────────────── */
    function board_acts() {
        return $this->template->rander("legal_compliance/board_acts");
    }

    function board_acts_data() {
        $acts = array(
            array("id" => 1, "ref" => "RES-BDP-2026-01", "title" => "Approval of FY 2026/27 Quarter 1 Subvention Allocation Schedule", "sitting" => "FY 2026/27 Q1 Sitting", "status" => "<span class='badge bg-success'>Enacted & Gazetted</span>", "date" => "2026-07-10"),
            array("id" => 2, "ref" => "RES-BDP-2026-02", "title" => "Ratification of Lugogo Indoor Arena Commercial Naming Rights Contract", "sitting" => "FY 2026/27 Q1 Sitting", "status" => "<span class='badge bg-success'>Enacted & Executed</span>", "date" => "2026-07-10"),
            array("id" => 3, "ref" => "RES-BDP-2026-03", "title" => "Establishment of Interim Normalization Tribunal for Netball Federation", "sitting" => "Special Sitting", "status" => "<span class='badge bg-success'>Enacted & Active</span>", "date" => "2026-08-01")
        );

        $result = array();
        foreach ($acts as $a) {
            $result[] = array(
                $a["id"],
                "<strong>" . $a["ref"] . "</strong>",
                "<strong>" . $a["title"] . "</strong>",
                "<span class='badge bg-primary'>" . $a["sitting"] . "</span>",
                $a["date"],
                $a["status"],
                anchor("#", "<i data-feather='file-text' class='icon-16'></i> View Resolution Minute", array("class" => "btn btn-outline-info btn-sm"))
            );
        }
        echo json_encode(array("data" => $result));
    }

    /* ─── 7. LEGAL COMPLIANCE REPORTS ────────────────────────────── */
    function reports() {
        return $this->template->rander("legal_compliance/reports");
    }

    function reports_data() {
        $reports = array(
            array("id" => 1, "code" => "LEG-REP-001", "name" => "Executive Legal Risk Exposure Briefing for General Secretary (UGX 895M)", "dept" => "Legal & Corporate Affairs", "type" => "Litigation Risk Audit", "freq" => "Quarterly", "status" => "<span class='badge bg-success'>Audit Ready</span>"),
            array("id" => 2, "code" => "LEG-REP-002", "name" => "National Sports Associations Statutory Compliance Return (Sports Act 2023)", "dept" => "Legal & Technical Ops", "type" => "Statutory Governance Return", "freq" => "Annual", "status" => "<span class='badge bg-success'>Audit Ready</span>"),
            array("id" => 3, "code" => "LEG-REP-003", "name" => "Commercial Contracts & MOUs Expiry & Renewal Pipeline Report", "dept" => "Legal & Procurement", "type" => "Contracts Audit", "freq" => "Monthly", "status" => "<span class='badge bg-success'>Audit Ready</span>")
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
}
