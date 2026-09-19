<?php

namespace App\Controllers;

class Facilities extends Security_Controller {

    protected $db;
    public $Facilities_model;
    public $Facility_bookings_model;
    public $Hostel_occupancies_model;
    public $Facility_inspections_model;
    public $Projects_model;

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
        $this->db = \Config\Database::connect();
        $this->Facilities_model = model("App\Models\Facilities_model");
        $this->Facility_bookings_model = model("App\Models\Facility_bookings_model");
        $this->Hostel_occupancies_model = model("App\Models\Hostel_occupancies_model");
        $this->Facility_inspections_model = model("App\Models\Facility_inspections_model");
        $this->Projects_model = model("App\Models\Projects_model");
    }

    function index() {
        return $this->template->rander("facilities/index");
    }

    function facilities_list_data() {
        $list_data = $this->Facilities_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_facility_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_facility_row($data) {
        $status_badge = "<span class='badge bg-success'>" . $data->status . "</span>";
        if ($data->status === "Under Renovation") {
            $status_badge = "<span class='badge bg-warning'>" . $data->status . "</span>";
        } else if ($data->status === "Maintenance Hold") {
            $status_badge = "<span class='badge bg-danger'>" . $data->status . "</span>";
        }

        $actions = modal_anchor(get_uri("facilities/facility_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Facility Details", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->facility_code . "</strong>",
            "<div><strong>" . $data->title . "</strong><br><small class='text-muted'>" . $data->description . "</small></div>",
            "<span class='badge bg-primary'>" . $data->category . "</span>",
            $data->location,
            number_format($data->capacity) . " Pax",
            to_currency($data->ntr_rate_per_day) . " / day",
            to_currency($data->caution_deposit_rate),
            $status_badge,
            $actions
        );
    }

    function facility_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Facilities_model->get_one($id);
        return $this->template->view("facilities/facility_modal_form", $view_data);
    }

    function save_facility() {
        $id = $this->request->getPost('id');
        $data = array(
            "facility_code" => $this->request->getPost('facility_code'),
            "title" => $this->request->getPost('title'),
            "category" => $this->request->getPost('category'),
            "location" => $this->request->getPost('location'),
            "capacity" => $this->request->getPost('capacity'),
            "ntr_rate_per_day" => unformat_currency($this->request->getPost('ntr_rate_per_day')),
            "caution_deposit_rate" => unformat_currency($this->request->getPost('caution_deposit_rate')),
            "status" => $this->request->getPost('status'),
            "description" => $this->request->getPost('description')
        );

        $save_id = $this->Facilities_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 2. VENUE BOOKINGS & CALENDAR ───────────────────────────── */
    function bookings() {
        return $this->template->rander("facilities/bookings");
    }

    function bookings_list_data() {
        $list_data = $this->Facility_bookings_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_booking_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_booking_row($data) {
        $pay_badge = "<span class='badge bg-warning'>" . $data->payment_status . "</span>";
        if ($data->payment_status === "FULLY_PAID") {
            $pay_badge = "<span class='badge bg-success'>FULLY PAID</span>";
        } else if ($data->payment_status === "PARTIALLY_PAID") {
            $pay_badge = "<span class='badge bg-info'>PARTIALLY PAID</span>";
        }

        $actions = modal_anchor(get_uri("facilities/booking_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Venue Booking", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->booking_reference . "</strong>",
            "<div><strong>" . $data->facility_name . "</strong><br><small class='text-muted'>" . $data->facility_location . "</small></div>",
            "<div><strong>" . $data->event_title . "</strong><br><small class='text-muted'>Client: " . $data->client_name . " (" . $data->client_type . ")</small></div>",
            "<small class='text-muted'>" . $data->start_date . " to " . $data->end_date . "</small>",
            "<span class='badge bg-secondary'>" . $data->tariff_category . "</span>",
            to_currency($data->total_fee_ugx),
            to_currency($data->caution_deposit_ugx),
            $pay_badge,
            $actions
        );
    }

    function booking_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Facility_bookings_model->get_one($id);
        $facilities = $this->Facilities_model->get_details()->getResult();
        $facilities_dropdown = array();
        foreach ($facilities as $fac) {
            $facilities_dropdown[$fac->id] = $fac->title . " (" . $fac->location . ")";
        }
        $view_data['facilities_dropdown'] = $facilities_dropdown;
        return $this->template->view("facilities/booking_modal_form", $view_data);
    }

    function save_booking() {
        $id = $this->request->getPost('id');
        $data = array(
            "booking_reference" => $this->request->getPost('booking_reference'),
            "facility_id" => $this->request->getPost('facility_id'),
            "client_name" => $this->request->getPost('client_name'),
            "client_type" => $this->request->getPost('client_type'),
            "event_title" => $this->request->getPost('event_title'),
            "start_date" => $this->request->getPost('start_date'),
            "end_date" => $this->request->getPost('end_date'),
            "tariff_category" => $this->request->getPost('tariff_category'),
            "total_fee_ugx" => unformat_currency($this->request->getPost('total_fee_ugx')),
            "caution_deposit_ugx" => unformat_currency($this->request->getPost('caution_deposit_ugx')),
            "payment_status" => $this->request->getPost('payment_status'),
            "technical_approval" => $this->request->getPost('technical_approval')
        );

        $save_id = $this->Facility_bookings_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 3. COMMERCIAL NTR INVOICING ────────────────────────────── */
    function invoices() {
        return $this->template->rander("facilities/invoices");
    }

    function invoices_list_data() {
        return $this->bookings_list_data();
    }

    /* ─── 4. LUGOGO HOSTELS ACCOMMODATION ───────────────────────── */
    function hostels() {
        return $this->template->rander("facilities/hostels");
    }

    function hostels_list_data() {
        $list_data = $this->Hostel_occupancies_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_hostel_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_hostel_row($data) {
        $status_badge = "<span class='badge bg-success'>" . $data->status . "</span>";
        if ($data->status === "Checked Out") {
            $status_badge = "<span class='badge bg-secondary'>" . $data->status . "</span>";
        }

        $actions = modal_anchor(get_uri("facilities/hostel_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Room Allocation", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->room_number . "</strong>",
            "<div><strong>" . $data->athlete_name . "</strong><br><small class='text-muted'>ID/NIN: " . ($data->nin_or_passport ?: "N/A") . "</small></div>",
            "<span class='badge bg-primary'>" . $data->federation_name . "</span>",
            $data->gender,
            "<small class='text-muted'>" . $data->check_in_date . " to " . $data->check_out_date . "</small>",
            ($data->key_issued ? "<span class='badge bg-info'>Key Issued</span>" : "<span class='badge bg-warning'>Key Returned</span>"),
            $status_badge,
            $actions
        );
    }

    function hostel_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Hostel_occupancies_model->get_one($id);
        return $this->template->view("facilities/hostel_modal_form", $view_data);
    }

    function save_hostel_occupancy() {
        $id = $this->request->getPost('id');
        $data = array(
            "room_number" => $this->request->getPost('room_number'),
            "athlete_name" => $this->request->getPost('athlete_name'),
            "nin_or_passport" => $this->request->getPost('nin_or_passport'),
            "federation_name" => $this->request->getPost('federation_name'),
            "gender" => $this->request->getPost('gender'),
            "check_in_date" => $this->request->getPost('check_in_date'),
            "check_out_date" => $this->request->getPost('check_out_date'),
            "status" => $this->request->getPost('status'),
            "key_issued" => $this->request->getPost('key_issued') ? 1 : 0
        );

        $save_id = $this->Hostel_occupancies_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 5. PRE & POST EVENT INSPECTIONS ────────────────────────── */
    function inspections() {
        return $this->template->rander("facilities/inspections");
    }

    function inspections_list_data() {
        $list_data = $this->Facility_inspections_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_inspection_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_inspection_row($data) {
        $safety_badge = ($data->safety_cleared == 1) ? "<span class='badge bg-success'>Passed & Cleared</span>" : "<span class='badge bg-danger'>Action Required</span>";

        $actions = modal_anchor(get_uri("facilities/inspection_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Inspection Checklist", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->booking_reference . "</strong>",
            "<div><strong>" . $data->event_title . "</strong><br><small class='text-muted'>Facility: " . $data->facility_name . "</small></div>",
            "<span class='badge bg-primary'>" . $data->inspection_type . "</span>",
            $data->inspector_name,
            $safety_badge,
            to_currency($data->damage_deduction_ugx),
            "<span class='badge bg-info'>" . $data->deposit_refund_status . "</span>",
            $actions
        );
    }

    function inspection_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Facility_inspections_model->get_one($id);
        $bookings = $this->Facility_bookings_model->get_details()->getResult();
        $bookings_dropdown = array();
        foreach ($bookings as $b) {
            $bookings_dropdown[$b->id] = $b->booking_reference . " - " . $b->event_title . " (" . $b->facility_name . ")";
        }
        $view_data['bookings_dropdown'] = $bookings_dropdown;
        return $this->template->view("facilities/inspection_modal_form", $view_data);
    }

    function save_inspection() {
        $id = $this->request->getPost('id');
        $data = array(
            "booking_id" => $this->request->getPost('booking_id'),
            "inspection_type" => $this->request->getPost('inspection_type'),
            "inspector_name" => $this->request->getPost('inspector_name'),
            "safety_cleared" => $this->request->getPost('safety_cleared') ? 1 : 0,
            "damage_deduction_ugx" => unformat_currency($this->request->getPost('damage_deduction_ugx')),
            "deposit_refund_status" => $this->request->getPost('deposit_refund_status'),
            "remarks" => $this->request->getPost('remarks')
        );

        $save_id = $this->Facility_inspections_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── 6. FACILITY CAPITAL PROJECTS & CONTRACTORS ──────────────── */
    function projects() {
        return $this->template->rander("facilities/projects");
    }

    function facility_projects_list_data() {
        $projects_table = $this->db->prefixTable('projects');
        $clients_table  = $this->db->prefixTable('clients');
        $relations_table = $this->db->prefixTable('project_facility_relations');
        $facilities_table = $this->db->prefixTable('facilities');

        $sql = "SELECT $projects_table.id, $projects_table.title, $projects_table.start_date, $projects_table.deadline, $projects_table.price, $projects_table.status_id,
                       $clients_table.company_name AS contractor_name,
                       $facilities_table.title AS facility_name, $facilities_table.location AS facility_location
                FROM $projects_table
                LEFT JOIN $clients_table ON $clients_table.id = $projects_table.client_id
                LEFT JOIN $relations_table ON $relations_table.project_id = $projects_table.id
                LEFT JOIN $facilities_table ON $facilities_table.id = $relations_table.facility_id
                WHERE $projects_table.deleted=0
                ORDER BY $projects_table.id DESC";

        $list = $this->db->query($sql)->getResult();
        $result = array();

        foreach ($list as $p) {
            $facility = $p->facility_name ? "<span class='badge bg-primary'>" . $p->facility_name . "</span>" : "<span class='badge bg-secondary'>General Infrastructure</span>";
            $contractor = $p->contractor_name ?: "Internal Engineering Unit";
            $status = ($p->status_id == 2) ? "<span class='badge bg-success'>Completed</span>" : "<span class='badge bg-warning'>In Progress</span>";

            $result[] = array(
                $p->id,
                "<strong>" . $p->title . "</strong>",
                $facility,
                "<strong>" . $contractor . "</strong>",
                to_currency($p->price),
                "<small class='text-muted'>" . ($p->start_date ?: "N/A") . " to " . ($p->deadline ?: "N/A") . "</small>",
                $status,
                anchor(get_uri("projects/view/" . $p->id), "<i data-feather='eye' class='icon-16'></i> View Project", array("class" => "btn btn-outline-info btn-sm"))
            );
        }

        echo json_encode(array("data" => $result));
    }

    /* ─── 7. FACILITY REPORTS (CONTRACTORS, EXPENSES & NTR) ────────── */
    function reports() {
        return $this->template->rander("facilities/reports");
    }

    function facility_reports_data() {
        $reports = array(
            array("id" => 1, "code" => "FAC-REP-001", "name" => "Facility NTR Revenue & Billing Statement (UGX 142.5M YTD)", "facility" => "Lugogo National Indoor Arena", "type" => "NTR Revenue Audit", "period" => "Monthly", "status" => "<span class='badge bg-success'>Audit Verified</span>"),
            array("id" => 2, "code" => "FAC-REP-002", "name" => "Lugogo Hostels Athlete Accommodation & Utility Usage Return", "facility" => "NCS Lugogo Hostels Block", "type" => "Hostel Occupancy Audit", "period" => "Monthly", "status" => "<span class='badge bg-success'>Audit Verified</span>"),
            array("id" => 3, "code" => "FAC-REP-003", "name" => "Facility Capital Projects, Contractors & Renovation Expenses (UGX 1.25B)", "facility" => "Lugogo Sports Ground & Arena", "type" => "Capital Expenditure Audit", "period" => "Quarterly", "status" => "<span class='badge bg-success'>Audit Verified</span>"),
            array("id" => 4, "code" => "FAC-REP-004", "name" => "AFCON 2027 Regional Stadium Development Project Costing Report", "facility" => "Hoima Regional Sports Hub", "type" => "Regional Project Report", "period" => "Quarterly", "status" => "<span class='badge bg-warning'>Under Review</span>"),
            array("id" => 5, "code" => "FAC-REP-005", "name" => "Pre/Post Event Safety Inspection & Security Caution Refunds Audit", "facility" => "All Sporting Facilities", "type" => "Safety & Damage Audit", "period" => "Monthly", "status" => "<span class='badge bg-success'>Audit Verified</span>")
        );

        $result = array();
        foreach ($reports as $r) {
            $download = anchor("#", "<i data-feather='download' class='icon-16'></i> Export PDF", array("class" => "btn btn-outline-primary btn-sm"));
            $result[] = array(
                $r["id"],
                "<strong>" . $r["code"] . "</strong>",
                "<strong>" . $r["name"] . "</strong>",
                $r["facility"],
                $r["type"],
                $r["period"],
                $r["status"],
                $download
            );
        }
        echo json_encode(array("data" => $result));
    }
}
