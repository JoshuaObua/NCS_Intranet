<?php

namespace App\Controllers;

class Engineering extends Security_Controller {

    protected $db;
    public $Engineering_work_orders_model;
    public $Engineering_assets_model;
    public $Engineering_capex_model;
    public $Engineering_inspections_model;
    public $Engineering_civil_assets_model;
    public $Engineering_electrical_assets_model;
    public $Engineering_technicians_model;

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
        $this->db = \Config\Database::connect();
        $this->Engineering_work_orders_model = model("App\Models\Engineering_work_orders_model");
        $this->Engineering_assets_model = model("App\Models\Engineering_assets_model");
        $this->Engineering_capex_model = model("App\Models\Engineering_capex_model");
        $this->Engineering_inspections_model = model("App\Models\Engineering_inspections_model");
        $this->Engineering_civil_assets_model = model("App\Models\Engineering_civil_assets_model");
        $this->Engineering_electrical_assets_model = model("App\Models\Engineering_electrical_assets_model");
        $this->Engineering_technicians_model = model("App\Models\Engineering_technicians_model");
    }

    function index() {
        return $this->work_orders();
    }

    /* ─── WORK ORDERS & MAINTENANCE ─────────────────────────────── */

    function work_orders() {
        return $this->template->rander("engineering/work_orders");
    }

    function work_orders_list_data() {
        $list_data = $this->Engineering_work_orders_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_work_order_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_work_order_row($data) {
        $status_badge = "<span class='badge bg-secondary'>PENDING</span>";
        if ($data->status === "IN_PROGRESS") {
            $status_badge = "<span class='badge bg-warning'>IN PROGRESS</span>";
        } else if ($data->status === "INSPECTED") {
            $status_badge = "<span class='badge bg-info'>INSPECTED</span>";
        } else if ($data->status === "COMPLETED") {
            $status_badge = "<span class='badge bg-success'>COMPLETED</span>";
        } else if ($data->status === "CLOSED") {
            $status_badge = "<span class='badge bg-dark'>CLOSED</span>";
        }

        $priority_badge = "<span class='badge bg-info'>" . $data->priority . "</span>";
        if ($data->priority === "EMERGENCY" || $data->priority === "HIGH") {
            $priority_badge = "<span class='badge bg-danger'>" . $data->priority . "</span>";
        }

        $actions = modal_anchor(get_uri("engineering/work_order_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Work Order", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->wo_number . "</strong>",
            $data->title,
            $data->category,
            $data->facility_location,
            $priority_badge,
            to_currency($data->estimated_cost),
            $data->requester_name ? $data->requester_name : "Senior Engineer",
            $status_badge,
            $actions
        );
    }

    function work_order_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Engineering_work_orders_model->get_one($id);
        $view_data['team_members_dropdown'] = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("user_type" => "staff"));

        return $this->template->view("engineering/work_order_modal_form", $view_data);
    }

    function save_work_order() {
        $this->validate_submitted_data(array(
            "title"             => "required",
            "category"          => "required",
            "facility_location" => "required",
            "description"       => "required"
        ));

        $id = $this->request->getPost('id');
        $category = $this->request->getPost('category');

        $wo_number = $this->request->getPost('wo_number');
        if (!$wo_number) {
            $count = count($this->Engineering_work_orders_model->get_details()->getResult()) + 1;
            $wo_number = "WO-ENG-2026-" . str_pad($count, 4, "0", STR_PAD_LEFT);
        }

        $data = array(
            "wo_number"         => $wo_number,
            "title"             => $this->request->getPost('title'),
            "category"          => $category,
            "priority"          => $this->request->getPost('priority') ?: "MEDIUM",
            "facility_location" => $this->request->getPost('facility_location'),
            "description"       => $this->request->getPost('description'),
            "assigned_to"       => (int)$this->request->getPost('assigned_to'),
            "requested_by"      => $this->login_user->id,
            "estimated_cost"    => unformat_currency($this->request->getPost('estimated_cost')),
            "status"            => $this->request->getPost('status') ?: "PENDING",
            "completion_date"   => $this->request->getPost('completion_date'),
            "remarks"           => $this->request->getPost('remarks')
        );

        $save_id = $this->Engineering_work_orders_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_work_order_row($this->Engineering_work_orders_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── INFRASTRUCTURE ASSETS ──────────────────────────────────── */

    function assets() {
        return $this->template->rander("engineering/assets");
    }

    function assets_list_data() {
        $list_data = $this->Engineering_assets_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_asset_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_asset_row($data) {
        $cond_badge = "<span class='badge bg-success'>" . $data->condition_rating . "</span>";
        if ($data->condition_rating === "FAIR") {
            $cond_badge = "<span class='badge bg-warning'>FAIR</span>";
        } else if ($data->condition_rating === "CRITICAL" || $data->condition_rating === "DAMAGED") {
            $cond_badge = "<span class='badge bg-danger'>" . $data->condition_rating . "</span>";
        }

        $actions = modal_anchor(get_uri("engineering/asset_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Asset Details", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->asset_code . "</strong>",
            $data->asset_name,
            $data->category,
            $data->facility_location,
            $cond_badge,
            to_currency($data->purchase_value),
            format_to_date($data->last_inspection_date),
            format_to_date($data->next_maintenance_date),
            $actions
        );
    }

    function asset_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Engineering_assets_model->get_one($id);

        return $this->template->view("engineering/asset_modal_form", $view_data);
    }

    function save_asset() {
        $this->validate_submitted_data(array(
            "asset_name"        => "required",
            "category"          => "required",
            "facility_location" => "required"
        ));

        $id = $this->request->getPost('id');
        $asset_code = $this->request->getPost('asset_code');
        if (!$asset_code) {
            $count = count($this->Engineering_assets_model->get_details()->getResult()) + 1;
            $asset_code = "AST-ENG-" . str_pad($count, 4, "0", STR_PAD_LEFT);
        }

        $data = array(
            "asset_code"            => $asset_code,
            "asset_name"            => $this->request->getPost('asset_name'),
            "category"              => $this->request->getPost('category'),
            "facility_location"     => $this->request->getPost('facility_location'),
            "condition_rating"      => $this->request->getPost('condition_rating') ?: "GOOD",
            "purchase_value"        => unformat_currency($this->request->getPost('purchase_value')),
            "last_inspection_date"  => $this->request->getPost('last_inspection_date'),
            "next_maintenance_date" => $this->request->getPost('next_maintenance_date'),
            "notes"                 => $this->request->getPost('notes')
        );

        $save_id = $this->Engineering_assets_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_asset_row($this->Engineering_assets_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── CIVIL ASSETS & LAND REGISTER (ASSISTANT ENGINEER CIVIL) ─── */

    function civil_assets() {
        return $this->template->rander("engineering/civil_assets");
    }

    function civil_assets_list_data() {
        $list_data = $this->Engineering_civil_assets_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_civil_asset_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_civil_asset_row($data) {
        $property_code = $data->asset_number ?? ($data->property_code ?? ("CIV-NCS-" . str_pad($data->id, 4, "0", STR_PAD_LEFT)));
        $asset_name = $data->asset_description ?? ($data->asset_name ?? "Civil Land Asset");
        $title_deed = $data->tag_number ?? ($data->title_deed_no ?? "N/A");
        $parcel_location = $data->category_segment3 ?? ($data->parcel_location ?? "NCS Facility Land");
        $acreage = isset($data->useful_life_years) ? ($data->useful_life_years > 0 ? $data->useful_life_years . " Yrs" : "Cadastral Plot") : (isset($data->acreage_ha) ? $data->acreage_ha . " Ha" : "N/A");
        $valuation = $data->adjusted_cost ?? ($data->valuation_ugx ?? 0.00);
        $boundary_status = $data->verification_status ?? ($data->boundary_status ?? "VERIFIED");
        $encroachment_status = $data->encroachment_status ?? "CLEAR";
        $last_survey_date = $data->verification_date ?? ($data->last_survey_date ?? substr($data->created_at, 0, 10));

        $boundary_badge = "<span class='badge bg-success'>" . $boundary_status . "</span>";
        if ($boundary_status === "UNVERIFIED" || $boundary_status === "DISPUTED") {
            $boundary_badge = "<span class='badge bg-danger'>" . $boundary_status . "</span>";
        } else if ($boundary_status === "IN_PROGRESS") {
            $boundary_badge = "<span class='badge bg-warning'>IN PROGRESS</span>";
        }

        $encroach_badge = "<span class='badge bg-success'>" . $encroachment_status . "</span>";
        if ($encroachment_status === "ENCROACHED" || $encroachment_status === "HIGH_RISK") {
            $encroach_badge = "<span class='badge bg-warning'>" . $encroachment_status . "</span>";
        }

        $actions = modal_anchor(get_uri("engineering/verify_civil_asset_modal"), "<i data-feather='check-circle' class='icon-16'></i>", array("class" => "edit text-primary", "title" => "Verify Civil Asset Boundary", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $property_code . "</strong>",
            "<div><strong>" . $asset_name . "</strong><br><small class='text-muted'>Deed: " . $title_deed . "</small></div>",
            $parcel_location,
            $acreage,
            to_currency($valuation),
            $boundary_badge,
            $encroach_badge,
            format_to_date($last_survey_date),
            $actions
        );
    }

    function verify_civil_asset_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Engineering_civil_assets_model->get_one($id);

        return $this->template->view("engineering/verify_civil_asset_modal", $view_data);
    }

    function save_civil_asset_verification() {
        $this->validate_submitted_data(array(
            "asset_name"      => "required",
            "parcel_location" => "required"
        ));

        $id = $this->request->getPost('id');
        $property_code = $this->request->getPost('property_code') ?: $this->request->getPost('asset_number');
        if (!$property_code) {
            $count = count($this->Engineering_civil_assets_model->get_details()->getResult()) + 1;
            $property_code = "CIV-NCS-" . str_pad($count, 4, "0", STR_PAD_LEFT);
        }

        $data = array(
            "asset_number"        => $property_code,
            "tag_number"          => $this->request->getPost('title_deed_no') ?: "NCS-CIV-001",
            "asset_description"   => $this->request->getPost('asset_name'),
            "category_segment3"   => $this->request->getPost('parcel_location'),
            "fb_cost"             => unformat_currency($this->request->getPost('valuation_ugx')),
            "adjusted_cost"       => unformat_currency($this->request->getPost('valuation_ugx')),
            "useful_life_years"   => intval($this->request->getPost('acreage_ha')),
            "verification_status" => $this->request->getPost('boundary_status') ?: "VERIFIED",
            "verification_date"   => $this->request->getPost('last_survey_date') ?: date("Y-m-d"),
            "notes"               => $this->request->getPost('notes')
        );

        $save_id = $this->Engineering_civil_assets_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_civil_asset_row($this->Engineering_civil_assets_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── ELECTRICAL MACHINERY & GENERATORS (ASSISTANT ENGINEER ELECTRICAL) ─── */

    function electrical_assets() {
        return $this->template->rander("engineering/electrical_assets");
    }

    function electrical_assets_list_data() {
        $list_data = $this->Engineering_electrical_assets_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_electrical_asset_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_electrical_asset_row($data) {
        $equipment_code = $data->asset_number ?? ($data->equipment_code ?? ("ELE-NCS-" . str_pad($data->id, 4, "0", STR_PAD_LEFT)));
        $equipment_name = $data->asset_description ?? ($data->equipment_name ?? "Electrical Machinery");
        $rating = $data->tag_number ?? ($data->rating_kva_or_kw ?? "N/A");
        $location = $data->category_segment3 ?? ($data->facility_location ?? "Lugogo Complex");
        $fuel_level = isset($data->fuel_level_percent) ? floatval($data->fuel_level_percent) : 100.00;
        $tank_cap = isset($data->fuel_tank_capacity_l) ? floatval($data->fuel_tank_capacity_l) : 200.00;
        $runtime = isset($data->running_hours) ? floatval($data->running_hours) : (isset($data->runtime_hours) ? floatval($data->runtime_hours) : 0.00);
        $ats_status = $data->verification_status ?? ($data->ats_status ?? "PASS_AUTO");
        $last_service = $data->last_service_date ?? substr($data->created_at, 0, 10);

        $ats_badge = "<span class='badge bg-success'>" . $ats_status . "</span>";
        if ($ats_status === "ATTENTION" || $ats_status === "MANUAL_ONLY") {
            $ats_badge = "<span class='badge bg-warning'>" . $ats_status . "</span>";
        } else if ($ats_status === "FAULTY") {
            $ats_badge = "<span class='badge bg-danger'>FAULTY</span>";
        }

        $fuel_display = $tank_cap > 0 ? ($tank_cap . " L (" . $fuel_level . "%)") : "N/A (Grid)";

        $actions = modal_anchor(get_uri("engineering/log_generator_modal"), "<i data-feather='zap' class='icon-16'></i>", array("class" => "edit text-primary", "title" => "Log Generator & Electrical Telemetry", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $equipment_code . "</strong>",
            "<div><strong>" . $equipment_name . "</strong><br><small class='text-muted'>" . $rating . "</small></div>",
            $location,
            $fuel_display,
            $runtime . " Hrs",
            $ats_badge,
            format_to_date($last_service),
            $actions
        );
    }

    function log_generator_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Engineering_electrical_assets_model->get_one($id);

        return $this->template->view("engineering/generator_log_modal", $view_data);
    }

    function save_generator_log() {
        $this->validate_submitted_data(array(
            "equipment_name"    => "required",
            "facility_location" => "required"
        ));

        $id = $this->request->getPost('id');
        $equipment_code = $this->request->getPost('equipment_code') ?: $this->request->getPost('asset_number');
        if (!$equipment_code) {
            $count = count($this->Engineering_electrical_assets_model->get_details()->getResult()) + 1;
            $equipment_code = "ELE-NCS-" . str_pad($count, 4, "0", STR_PAD_LEFT);
        }

        $data = array(
            "asset_number"        => $equipment_code,
            "tag_number"          => $this->request->getPost('rating_kva_or_kw') ?: "60 KVA",
            "asset_description"   => $this->request->getPost('equipment_name'),
            "category_segment3"   => $this->request->getPost('facility_location'),
            "fb_cost"             => 10000000.00,
            "adjusted_cost"       => 10000000.00,
            "fuel_level_percent"  => floatval($this->request->getPost('fuel_level_percent')),
            "running_hours"       => floatval($this->request->getPost('runtime_hours')),
            "verification_status" => $this->request->getPost('ats_status') ?: "VERIFIED",
            "last_service_date"   => $this->request->getPost('last_service_date') ?: date("Y-m-d"),
            "notes"               => $this->request->getPost('notes')
        );

        $save_id = $this->Engineering_electrical_assets_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_electrical_asset_row($this->Engineering_electrical_assets_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── TECHNICIAN FIELD ROSTERS ───────────────────────────────── */

    function technicians() {
        return $this->template->rander("engineering/technicians");
    }

    function technicians_list_data() {
        $list_data = $this->Engineering_technicians_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_technician_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_technician_row($data) {
        $full_name = $data->name ?? ($data->full_name ?? "Technician");
        $trade = $data->trade_specialization ?? ($data->trade ?? "ELECTRICAL");
        $phone = $data->phone ?? ($data->phone_number ?? "N/A");
        $certification = $data->trade_group ?? ($data->skill_certification ?? "Trade Certificate");
        $station = $data->assigned_venue ?? ($data->assigned_station ?? "Main Complex");
        $duty_status = $data->status ?? ($data->duty_status ?? "ON_DUTY");

        $trade_badge = "<span class='badge bg-primary'>" . $trade . "</span>";
        if (strpos($trade, "PLUMB") !== false) {
            $trade_badge = "<span class='badge bg-info'>" . $trade . "</span>";
        } else if (strpos($trade, "MASON") !== false || strpos($trade, "CIVIL") !== false) {
            $trade_badge = "<span class='badge bg-secondary'>" . $trade . "</span>";
        } else if (strpos($trade, "HVAC") !== false) {
            $trade_badge = "<span class='badge bg-dark'>" . $trade . "</span>";
        }

        $duty_badge = "<span class='badge bg-success'>" . $duty_status . "</span>";
        if ($duty_status === "ON_CALL" || $duty_status === "AVAILABLE") {
            $duty_badge = "<span class='badge bg-warning'>" . $duty_status . "</span>";
        } else if ($duty_status === "OFF_DUTY") {
            $duty_badge = "<span class='badge bg-secondary'>OFF DUTY</span>";
        }

        $actions = modal_anchor(get_uri("engineering/technician_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Technician Roster", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $full_name . "</strong>",
            $trade_badge,
            $phone,
            $certification,
            $station,
            $duty_badge,
            $actions
        );
    }

    function technician_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Engineering_technicians_model->get_one($id);

        return $this->template->view("engineering/technician_modal_form", $view_data);
    }

    function save_technician() {
        $this->validate_submitted_data(array(
            "full_name"        => "required",
            "trade"            => "required",
            "assigned_station" => "required"
        ));

        $id = $this->request->getPost('id');
        $data = array(
            "name"                 => $this->request->getPost('full_name'),
            "trade_specialization" => $this->request->getPost('trade'),
            "trade_group"          => $this->request->getPost('skill_certification') ?: "TECHNICAL",
            "phone"                => $this->request->getPost('phone_number') ?: "N/A",
            "assigned_venue"       => $this->request->getPost('assigned_station'),
            "status"               => $this->request->getPost('duty_status') ?: "ON_DUTY",
            "notes"                => $this->request->getPost('notes')
        );

        $save_id = $this->Engineering_technicians_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_technician_row($this->Engineering_technicians_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── CAPEX REQUISITIONS ─────────────────────────────────────── */

    function capex() {
        return $this->template->rander("engineering/capex");
    }

    function capex_list_data() {
        $list_data = $this->Engineering_capex_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_capex_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_capex_row($data) {
        $status_badge = "<span class='badge bg-warning'>Pending Senior Engineer HOD</span>";
        if ($data->status === "HOD_APPROVED") {
            $status_badge = "<span class='badge bg-info'>HOD Approved (Pending GS)</span>";
        } else if ($data->status === "GS_APPROVED") {
            $status_badge = "<span class='badge bg-success'>GS Approved (Awarded)</span>";
        } else if ($data->status === "REJECTED") {
            $status_badge = "<span class='badge bg-danger'>REJECTED</span>";
        }

        $actions = modal_anchor(get_uri("engineering/capex_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit CapEx Requisition", "data-post-id" => $data->id));
        if ($data->status !== "GS_APPROVED" && $data->status !== "REJECTED") {
            $actions .= " " . modal_anchor(get_uri("engineering/capex_approval_modal"), "<i data-feather='check-square' class='icon-16'></i>", array("class" => "edit text-primary", "title" => "Process CapEx Approval", "data-post-id" => $data->id));
        }

        return array(
            $data->id,
            "<strong>" . $data->capex_ref_no . "</strong>",
            $data->project_title,
            $data->facility_location,
            to_currency($data->estimated_budget),
            $data->requester_name ?: "Senior Engineer HOD",
            format_to_date($data->created_at),
            $status_badge,
            $actions
        );
    }

    function capex_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Engineering_capex_model->get_one($id);

        return $this->template->view("engineering/capex_modal_form", $view_data);
    }

    function save_capex() {
        $this->validate_submitted_data(array(
            "project_title"     => "required",
            "facility_location" => "required",
            "estimated_budget"  => "required",
            "justification"     => "required"
        ));

        $id = $this->request->getPost('id');
        $capex_ref_no = $this->request->getPost('capex_ref_no');
        if (!$capex_ref_no) {
            $count = count($this->Engineering_capex_model->get_details()->getResult()) + 1;
            $capex_ref_no = "CAPEX-ENG-2026-" . str_pad($count, 3, "0", STR_PAD_LEFT);
        }

        $data = array(
            "capex_ref_no"      => $capex_ref_no,
            "project_title"     => $this->request->getPost('project_title'),
            "facility_location" => $this->request->getPost('facility_location'),
            "estimated_budget"  => unformat_currency($this->request->getPost('estimated_budget')),
            "justification"     => $this->request->getPost('justification'),
            "department_id"     => 7,
            "requested_by"      => $this->login_user->id,
            "status"            => "PENDING_HOD"
        );

        $save_id = $this->Engineering_capex_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_capex_row($this->Engineering_capex_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    function capex_approval_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Engineering_capex_model->get_details(array("id" => $id))->getRow();

        return $this->template->view("engineering/capex_approval_modal", $view_data);
    }

    function save_capex_approval() {
        $id = $this->request->getPost('id');
        $action = $this->request->getPost('action'); // 'HOD_APPROVE', 'GS_APPROVE', 'REJECT'
        $comments = $this->request->getPost('comments');

        $model = $this->Engineering_capex_model->get_one($id);
        if (!$model->id) {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
            return;
        }

        $data = array();
        if ($action === "HOD_APPROVE") {
            $data['status'] = "HOD_APPROVED";
            $data['hod_user_id'] = $this->login_user->id;
            $data['hod_comments'] = $comments;
            $data['hod_decided_at'] = date("Y-m-d H:i:s");
        } else if ($action === "GS_APPROVE") {
            $data['status'] = "GS_APPROVED";
            $data['gs_user_id'] = $this->login_user->id;
            $data['gs_comments'] = $comments;
            $data['gs_decided_at'] = date("Y-m-d H:i:s");
        } else if ($action === "REJECT") {
            $data['status'] = "REJECTED";
            $data['hod_comments'] = $comments;
        }

        $this->Engineering_capex_model->ci_save($data, $id);
        echo json_encode(array("success" => true, "data" => $this->_make_capex_row($this->Engineering_capex_model->get_details(array("id" => $id))->getRow()), "id" => $id, "message" => app_lang('record_saved')));
    }

    /* ─── FACILITY INSPECTIONS & EVENT READINESS ──────────────────── */

    function inspections() {
        return $this->template->rander("engineering/inspections");
    }

    function inspections_list_data() {
        $list_data = $this->Engineering_inspections_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_inspection_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_inspection_row($data) {
        $status_badge = "<span class='badge bg-success'>PASSED</span>";
        if ($data->status === "ACTION_REQUIRED") {
            $status_badge = "<span class='badge bg-warning'>ACTION REQUIRED</span>";
        } else if ($data->status === "FAILED") {
            $status_badge = "<span class='badge bg-danger'>FAILED</span>";
        }

        $actions = modal_anchor(get_uri("engineering/inspection_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Inspection Record", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->inspection_code . "</strong>",
            $data->event_or_facility,
            format_to_date($data->inspection_date),
            $data->inspector_name ?: "Senior Engineer HOD",
            "<span class='badge bg-info'>" . $data->civil_safety_status . "</span>",
            "<span class='badge bg-info'>" . $data->electrical_safety_status . "</span>",
            "<strong class='text-primary'>" . $data->readiness_score . "%</strong>",
            $status_badge,
            $actions
        );
    }

    function inspection_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Engineering_inspections_model->get_one($id);

        return $this->template->view("engineering/inspection_modal_form", $view_data);
    }

    function save_inspection() {
        $this->validate_submitted_data(array(
            "event_or_facility" => "required",
            "inspection_date"   => "required"
        ));

        $id = $this->request->getPost('id');
        $inspection_code = $this->request->getPost('inspection_code');
        if (!$inspection_code) {
            $count = count($this->Engineering_inspections_model->get_details()->getResult()) + 1;
            $inspection_code = "INSP-2026-" . str_pad($count, 3, "0", STR_PAD_LEFT);
        }

        $data = array(
            "inspection_code"           => $inspection_code,
            "event_or_facility"         => $this->request->getPost('event_or_facility'),
            "inspection_date"           => $this->request->getPost('inspection_date'),
            "inspector_user_id"         => $this->login_user->id,
            "civil_safety_status"       => $this->request->getPost('civil_safety_status') ?: "PASS",
            "electrical_safety_status"  => $this->request->getPost('electrical_safety_status') ?: "PASS",
            "readiness_score"           => floatval($this->request->getPost('readiness_score')) ?: 100.00,
            "findings"                  => $this->request->getPost('findings'),
            "action_items"              => $this->request->getPost('action_items'),
            "status"                    => $this->request->getPost('status') ?: "PASSED"
        );

        $save_id = $this->Engineering_inspections_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_inspection_row($this->Engineering_inspections_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── ENGINEERING HOD PERSONAL ACTIVITY TIMELINE ────────────── */

    function activities() {
        $view_data['work_orders'] = $this->Engineering_work_orders_model->get_details()->getResult();
        $view_data['capex_list'] = $this->Engineering_capex_model->get_details()->getResult();
        $view_data['inspections'] = $this->Engineering_inspections_model->get_details()->getResult();

        return $this->template->rander("engineering/activities", $view_data);
    }

    /* ─── STATUTORY ENGINEERING & CONDITION REPORTS ─────────────── */

    function reports() {
        $view_data['total_work_orders'] = count($this->Engineering_work_orders_model->get_details()->getResult());
        $view_data['pending_work_orders'] = count($this->Engineering_work_orders_model->get_details(array("status" => "PENDING"))->getResult());
        $view_data['total_assets'] = count($this->Engineering_assets_model->get_details()->getResult());
        $view_data['critical_assets'] = count($this->Engineering_assets_model->get_details(array("condition_rating" => "CRITICAL"))->getResult());
        $view_data['total_civil_assets'] = count($this->Engineering_civil_assets_model->get_details()->getResult());
        $view_data['total_electrical_assets'] = count($this->Engineering_electrical_assets_model->get_details()->getResult());
        $view_data['total_technicians'] = count($this->Engineering_technicians_model->get_details()->getResult());
        
        $view_data['work_orders'] = $this->Engineering_work_orders_model->get_details()->getResult();
        $view_data['assets'] = $this->Engineering_assets_model->get_details()->getResult();
        $view_data['inspections'] = $this->Engineering_inspections_model->get_details()->getResult();
        $view_data['civil_assets'] = $this->Engineering_civil_assets_model->get_details()->getResult();
        $view_data['electrical_assets'] = $this->Engineering_electrical_assets_model->get_details()->getResult();

        return $this->template->rander("engineering/reports", $view_data);
    }
}
