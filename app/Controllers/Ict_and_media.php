<?php

namespace App\Controllers;

class Ict_and_media extends Security_Controller {

    protected $db;
    public $Ict_equipment_model;
    public $Ict_issuances_model;
    public $Ict_maintenance_model;
    public $Ict_helpdesk_model;
    public $Ict_expenses_model;

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
        $this->db = \Config\Database::connect();
        $this->Ict_equipment_model = model("App\Models\Ict_equipment_model");
        $this->Ict_issuances_model = model("App\Models\Ict_issuances_model");
        $this->Ict_maintenance_model = model("App\Models\Ict_maintenance_model");
        $this->Ict_helpdesk_model = model("App\Models\Ict_helpdesk_model");
        $this->Ict_expenses_model = model("App\Models\Ict_expenses_model");
    }

    function index() {
        return $this->hardware();
    }

    /* ─── IT & MEDIA EQUIPMENT & CONSUMABLES INVENTORY ───────────── */
    function hardware() {
        return $this->template->rander("ict_and_media/hardware");
    }

    function hardware_list_data() {
        $list_data = $this->Ict_equipment_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_hardware_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_hardware_row($data) {
        $category_badge = "<span class='badge bg-primary'>" . str_replace("_", " ", $data->category) . "</span>";
        if ($data->category === "DRONES_GIMBALS_CRANES") {
            $category_badge = "<span class='badge bg-warning'>DRONES / GIMBALS / CRANES</span>";
        } else if ($data->category === "CAMERAS_PHOTOGRAPHY") {
            $category_badge = "<span class='badge bg-info'>CAMERAS & MEDIA</span>";
        } else if ($data->category === "SECURITY_CCTV") {
            $category_badge = "<span class='badge bg-danger'>CCTV & SECURITY</span>";
        } else if ($data->category === "NETWORKING") {
            $category_badge = "<span class='badge bg-dark'>NETWORKING</span>";
        }

        $status_badge = "<span class='badge bg-success'>" . $data->status . "</span>";
        if ($data->status === "ISSUED") {
            $status_badge = "<span class='badge bg-warning'>CHECKED OUT</span>";
        } else if ($data->status === "IN_REPAIR") {
            $status_badge = "<span class='badge bg-danger'>IN REPAIR</span>";
        }

        $actions = modal_anchor(get_uri("ict_and_media/hardware_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit IT / Media Equipment", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->item_code . "</strong>",
            "<div><strong>" . $data->item_name . "</strong><br><small class='text-muted'>" . ($data->brand_model ?: "N/A") . " (SN: " . ($data->serial_number ?: "N/A") . ")</small></div>",
            $category_badge,
            to_currency($data->purchase_cost),
            "<span class='badge bg-info'>" . $data->condition_rating . "</span>",
            $data->location_assigned,
            $status_badge,
            $actions
        );
    }

    function hardware_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Ict_equipment_model->get_one($id);

        return $this->template->view("ict_and_media/hardware_modal_form", $view_data);
    }

    function save_hardware() {
        $this->validate_submitted_data(array(
            "item_name" => "required",
            "category"  => "required"
        ));

        $id = $this->request->getPost('id');
        $item_code = $this->request->getPost('item_code');
        if (!$item_code) {
            $count = count($this->Ict_equipment_model->get_details()->getResult()) + 1;
            $item_code = "ICT-EQP-" . str_pad($count, 4, "0", STR_PAD_LEFT);
        }

        $data = array(
            "item_code"         => $item_code,
            "item_name"         => $this->request->getPost('item_name'),
            "category"          => $this->request->getPost('category'),
            "brand_model"       => $this->request->getPost('brand_model'),
            "serial_number"     => $this->request->getPost('serial_number'),
            "specifications"    => $this->request->getPost('specifications'),
            "purchase_cost"     => unformat_currency($this->request->getPost('purchase_cost')),
            "purchase_date"     => $this->request->getPost('purchase_date') ?: date("Y-m-d"),
            "condition_rating"  => $this->request->getPost('condition_rating') ?: "GOOD",
            "status"            => $this->request->getPost('status') ?: "AVAILABLE",
            "location_assigned" => $this->request->getPost('location_assigned') ?: "ICT Central Store",
            "notes"             => $this->request->getPost('notes')
        );

        $save_id = $this->Ict_equipment_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_hardware_row($this->Ict_equipment_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── EQUIPMENT ISSUANCES & DISPATCH ─────────────────────────── */
    function issuances() {
        return $this->template->rander("ict_and_media/issuances");
    }

    function issuances_list_data() {
        $list_data = $this->Ict_issuances_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_issuance_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_issuance_row($data) {
        $status_badge = "<span class='badge bg-warning'>CHECKED OUT</span>";
        if ($data->status === "RETURNED") {
            $status_badge = "<span class='badge bg-success'>RETURNED</span>";
        } else if ($data->status === "OVERDUE") {
            $status_badge = "<span class='badge bg-danger'>OVERDUE</span>";
        }

        $actions = modal_anchor(get_uri("ict_and_media/issuance_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Equipment Issuance", "data-post-id" => $data->id));
        if ($data->status === "CHECKED_OUT" || $data->status === "OVERDUE") {
            $actions .= " " . modal_anchor(get_uri("ict_and_media/return_equipment_modal"), "<i data-feather='check-square' class='icon-16'></i>", array("class" => "edit text-success", "title" => "Check In / Return Equipment", "data-post-id" => $data->id));
        }

        return array(
            $data->id,
            "<strong>" . $data->dispatch_ref . "</strong>",
            "<div><strong>" . ($data->item_name ?: "Equipment #" . $data->equipment_id) . "</strong><br><small class='text-muted'>" . ($data->item_code ?: "") . "</small></div>",
            $data->recipient_name,
            $data->target_department_name,
            $data->purpose,
            format_to_date($data->issue_date),
            format_to_date($data->expected_return_date),
            $status_badge,
            $actions
        );
    }

    function issuance_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Ict_issuances_model->get_one($id);
        $view_data['equipment_dropdown'] = $this->Ict_equipment_model->get_details(array("status" => "AVAILABLE"))->getResult();
        $view_data['team_members_dropdown'] = $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id", array("user_type" => "staff"));

        return $this->template->view("ict_and_media/issuance_modal_form", $view_data);
    }

    function save_issuance() {
        $this->validate_submitted_data(array(
            "equipment_id"           => "required",
            "recipient_name"         => "required",
            "target_department_name" => "required",
            "purpose"                => "required"
        ));

        $id = $this->request->getPost('id');
        $dispatch_ref = $this->request->getPost('dispatch_ref');
        if (!$dispatch_ref) {
            $count = count($this->Ict_issuances_model->get_details()->getResult()) + 1;
            $dispatch_ref = "DISP-ICT-2026-" . str_pad($count, 3, "0", STR_PAD_LEFT);
        }

        $equipment_id = (int)$this->request->getPost('equipment_id');

        $data = array(
            "dispatch_ref"           => $dispatch_ref,
            "equipment_id"           => $equipment_id,
            "recipient_name"         => $this->request->getPost('recipient_name'),
            "target_department_name" => $this->request->getPost('target_department_name'),
            "purpose"                => $this->request->getPost('purpose'),
            "issue_date"             => $this->request->getPost('issue_date') ?: date("Y-m-d"),
            "expected_return_date"   => $this->request->getPost('expected_return_date'),
            "condition_on_issue"     => $this->request->getPost('condition_on_issue') ?: "EXCELLENT",
            "issued_by_user_id"      => $this->login_user->id,
            "status"                 => "CHECKED_OUT",
            "remarks"                => $this->request->getPost('remarks')
        );

        $save_id = $this->Ict_issuances_model->ci_save($data, $id);
        if ($save_id) {
            // Update equipment status to ISSUED
            $this->Ict_equipment_model->ci_save(array("status" => "ISSUED"), $equipment_id);

            echo json_encode(array("success" => true, "data" => $this->_make_issuance_row($this->Ict_issuances_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    function return_equipment_modal() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Ict_issuances_model->get_details(array("id" => $id))->getRow();

        return $this->template->view("ict_and_media/return_equipment_modal", $view_data);
    }

    function save_equipment_return() {
        $id = $this->request->getPost('id');
        $issuance = $this->Ict_issuances_model->get_one($id);
        if (!$issuance->id) {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
            return;
        }

        $data = array(
            "status"               => "RETURNED",
            "actual_return_date"   => date("Y-m-d"),
            "condition_on_return" => $this->request->getPost('condition_on_return') ?: "GOOD",
            "remarks"              => $this->request->getPost('remarks')
        );

        $this->Ict_issuances_model->ci_save($data, $id);
        // Mark equipment AVAILABLE again
        $this->Ict_equipment_model->ci_save(array("status" => "AVAILABLE"), $issuance->equipment_id);

        echo json_encode(array("success" => true, "data" => $this->_make_issuance_row($this->Ict_issuances_model->get_details(array("id" => $id))->getRow()), "id" => $id, "message" => app_lang('record_saved')));
    }

    /* ─── MAINTENANCE & REPAIR REQUISITIONS ──────────────────────── */
    function maintenance() {
        return $this->template->rander("ict_and_media/maintenance");
    }

    function maintenance_list_data() {
        $list_data = $this->Ict_maintenance_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_maintenance_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_maintenance_row($data) {
        $status_badge = "<span class='badge bg-secondary'>SUBMITTED</span>";
        if ($data->status === "IN_REPAIR") {
            $status_badge = "<span class='badge bg-warning'>IN REPAIR</span>";
        } else if ($data->status === "COMPLETED") {
            $status_badge = "<span class='badge bg-success'>COMPLETED</span>";
        } else if ($data->status === "REJECTED") {
            $status_badge = "<span class='badge bg-danger'>REJECTED</span>";
        }

        $actions = modal_anchor(get_uri("ict_and_media/maintenance_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit Repair Requisition", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->req_no . "</strong>",
            $data->equipment_name,
            $data->requester_name . " (" . $data->department_name . ")",
            "<span class='badge bg-info'>" . str_replace("_", " ", $data->fault_category) . "</span>",
            "<span class='badge bg-warning'>" . $data->service_type . "</span>",
            to_currency($data->estimated_cost),
            $status_badge,
            $actions
        );
    }

    function maintenance_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Ict_maintenance_model->get_one($id);
        $view_data['equipment_dropdown'] = $this->Ict_equipment_model->get_details()->getResult();

        return $this->template->view("ict_and_media/maintenance_modal_form", $view_data);
    }

    function save_maintenance() {
        $this->validate_submitted_data(array(
            "equipment_name"  => "required",
            "requester_name"  => "required",
            "department_name" => "required",
            "fault_description" => "required"
        ));

        $id = $this->request->getPost('id');
        $req_no = $this->request->getPost('req_no');
        if (!$req_no) {
            $count = count($this->Ict_maintenance_model->get_details()->getResult()) + 1;
            $req_no = "REQ-MAINT-2026-" . str_pad($count, 3, "0", STR_PAD_LEFT);
        }

        $data = array(
            "req_no"            => $req_no,
            "equipment_id"      => (int)$this->request->getPost('equipment_id'),
            "equipment_name"    => $this->request->getPost('equipment_name'),
            "requester_name"    => $this->request->getPost('requester_name'),
            "department_name"   => $this->request->getPost('department_name'),
            "fault_category"    => $this->request->getPost('fault_category') ?: "HARDWARE_BREAKAGE",
            "fault_description" => $this->request->getPost('fault_description'),
            "priority"          => $this->request->getPost('priority') ?: "MEDIUM",
            "service_type"      => $this->request->getPost('service_type') ?: "INTERNAL_IT",
            "vendor_name"       => $this->request->getPost('vendor_name'),
            "estimated_cost"    => unformat_currency($this->request->getPost('estimated_cost')),
            "status"            => $this->request->getPost('status') ?: "SUBMITTED",
            "resolution_notes"  => $this->request->getPost('resolution_notes')
        );

        $save_id = $this->Ict_maintenance_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_maintenance_row($this->Ict_maintenance_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── IT HELPDESK TICKETS ────────────────────────────────────── */
    function helpdesk() {
        return $this->template->rander("ict_and_media/helpdesk");
    }

    function helpdesk_list_data() {
        $list_data = $this->Ict_helpdesk_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_helpdesk_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_helpdesk_row($data) {
        $status_badge = "<span class='badge bg-warning'>OPEN</span>";
        if ($data->status === "IN_PROGRESS") {
            $status_badge = "<span class='badge bg-info'>IN PROGRESS</span>";
        } else if ($data->status === "RESOLVED") {
            $status_badge = "<span class='badge bg-success'>RESOLVED</span>";
        }

        $priority_badge = "<span class='badge bg-info'>" . $data->priority . "</span>";
        if ($data->priority === "HIGH" || $data->priority === "URGENT") {
            $priority_badge = "<span class='badge bg-danger'>" . $data->priority . "</span>";
        }

        $actions = modal_anchor(get_uri("ict_and_media/helpdesk_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit IT Ticket", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->ticket_number . "</strong>",
            $data->subject,
            $data->requester_name . " (" . $data->department_name . ")",
            $data->category,
            $priority_badge,
            $data->assigned_to_name ?: "IT Systems Admin",
            $status_badge,
            $actions
        );
    }

    function helpdesk_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Ict_helpdesk_model->get_one($id);

        return $this->template->view("ict_and_media/helpdesk_modal_form", $view_data);
    }

    function save_helpdesk() {
        $this->validate_submitted_data(array(
            "subject"         => "required",
            "requester_name"  => "required",
            "department_name" => "required",
            "description"     => "required"
        ));

        $id = $this->request->getPost('id');
        $ticket_number = $this->request->getPost('ticket_number');
        if (!$ticket_number) {
            $count = count($this->Ict_helpdesk_model->get_details()->getResult()) + 1;
            $ticket_number = "T-2026-" . str_pad($count, 4, "0", STR_PAD_LEFT);
        }

        $data = array(
            "ticket_number"    => $ticket_number,
            "subject"          => $this->request->getPost('subject'),
            "requester_name"   => $this->request->getPost('requester_name'),
            "department_name"  => $this->request->getPost('department_name'),
            "category"         => $this->request->getPost('category') ?: "HARDWARE",
            "priority"         => $this->request->getPost('priority') ?: "MEDIUM",
            "description"      => $this->request->getPost('description'),
            "assigned_to_name" => $this->request->getPost('assigned_to_name') ?: "IT Systems Admin",
            "status"           => $this->request->getPost('status') ?: "OPEN",
            "resolution_notes" => $this->request->getPost('resolution_notes')
        );

        $save_id = $this->Ict_helpdesk_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_helpdesk_row($this->Ict_helpdesk_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── ICT & MEDIA EXPENSES ───────────────────────────────────── */
    function expenses() {
        return $this->template->rander("ict_and_media/expenses");
    }

    function expenses_list_data() {
        $list_data = $this->Ict_expenses_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_expense_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_expense_row($data) {
        $actions = modal_anchor(get_uri("ict_and_media/expense_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => "Edit ICT Expense", "data-post-id" => $data->id));

        return array(
            $data->id,
            "<strong>" . $data->expense_ref . "</strong>",
            $data->title,
            "<span class='badge bg-info'>" . str_replace("_", " ", $data->category) . "</span>",
            to_currency($data->amount_ugx),
            format_to_date($data->expense_date),
            $data->vendor_supplier ?: "N/A",
            $data->approved_by ?: "IT Manager",
            $actions
        );
    }

    function expense_modal_form() {
        $id = $this->request->getPost('id');
        $view_data['model_info'] = $this->Ict_expenses_model->get_one($id);

        return $this->template->view("ict_and_media/expense_modal_form", $view_data);
    }

    function save_expense() {
        $this->validate_submitted_data(array(
            "title"      => "required",
            "category"   => "required",
            "amount_ugx" => "required"
        ));

        $id = $this->request->getPost('id');
        $expense_ref = $this->request->getPost('expense_ref');
        if (!$expense_ref) {
            $count = count($this->Ict_expenses_model->get_details()->getResult()) + 1;
            $expense_ref = "EXP-ICT-2026-" . str_pad($count, 3, "0", STR_PAD_LEFT);
        }

        $data = array(
            "expense_ref"        => $expense_ref,
            "title"              => $this->request->getPost('title'),
            "category"           => $this->request->getPost('category'),
            "amount_ugx"         => unformat_currency($this->request->getPost('amount_ugx')),
            "expense_date"       => $this->request->getPost('expense_date') ?: date("Y-m-d"),
            "vendor_supplier"    => $this->request->getPost('vendor_supplier'),
            "invoice_receipt_no" => $this->request->getPost('invoice_receipt_no'),
            "approved_by"        => $this->request->getPost('approved_by') ?: "IT Manager",
            "notes"              => $this->request->getPost('notes')
        );

        $save_id = $this->Ict_expenses_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_make_expense_row($this->Ict_expenses_model->get_details(array("id" => $save_id))->getRow()), "id" => $save_id, "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang('error_occurred')));
        }
    }

    /* ─── INFRASTRUCTURE UPTIME & BACKUPS ────────────────────────── */
    function infrastructure() {
        $view_data['uptime'] = "99.98%";
        $view_data['last_backup'] = date("Y-m-d 02:00:00");
        $view_data['open_tickets'] = count($this->Ict_helpdesk_model->get_details(array("status" => "OPEN"))->getResult());
        $view_data['active_users'] = 42;

        return $this->template->rander("ict_and_media/infrastructure", $view_data);
    }

    /* ─── ICT ACTIVITY LOG ───────────────────────────────────────── */
    function activities() {
        $view_data['equipment'] = $this->Ict_equipment_model->get_details()->getResult();
        $view_data['issuances'] = $this->Ict_issuances_model->get_details()->getResult();
        $view_data['maintenance'] = $this->Ict_maintenance_model->get_details()->getResult();

        return $this->template->rander("ict_and_media/activities", $view_data);
    }

    /* ─── ICT & MEDIA REPORTS & ANALYTICS ────────────────────────── */
    function reports() {
        $view_data['total_equipment'] = count($this->Ict_equipment_model->get_details()->getResult());
        $view_data['available_equipment'] = count($this->Ict_equipment_model->get_details(array("status" => "AVAILABLE"))->getResult());
        $view_data['issued_equipment'] = count($this->Ict_equipment_model->get_details(array("status" => "ISSUED"))->getResult());
        $view_data['open_tickets'] = count($this->Ict_helpdesk_model->get_details(array("status" => "OPEN"))->getResult());

        $view_data['equipment'] = $this->Ict_equipment_model->get_details()->getResult();
        $view_data['issuances'] = $this->Ict_issuances_model->get_details()->getResult();
        $view_data['maintenance'] = $this->Ict_maintenance_model->get_details()->getResult();
        $view_data['expenses'] = $this->Ict_expenses_model->get_details()->getResult();

        return $this->template->rander("ict_and_media/reports", $view_data);
    }
}
