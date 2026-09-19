<?php

namespace App\Controllers;

class Stores_inventory extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_team_members();
    }

    /* ─── INVENTORY DASHBOARD & ITEMS ────────────────────────────── */

    function index() {
        $view_data['category_summary'] = $this->Store_inventory_model->get_category_summary();
        $view_data['low_stock_count']  = count($this->Store_inventory_model->get_details(array("low_stock" => true))->getResult());
        return $this->template->rander("stores_inventory/index", $view_data);
    }

    function create_item() {
        return $this->modal_form(0);
    }

    function create_page() {
        $view_data['category_dropdown'] = array(
            "sports_gear"        => app_lang("sports_gear"),
            "engineering_spares" => app_lang("engineering_spares"),
            "ict_consumables"    => app_lang("ict_consumables"),
            "office_supplies"    => app_lang("office_supplies"),
        );

        $view_data['condition_dropdown'] = array(
            "good"      => app_lang("condition_good"),
            "fair"      => app_lang("condition_fair"),
            "defective" => app_lang("condition_defective"),
            "obsolete"  => app_lang("condition_obsolete"),
        );

        $view_data['departments_dropdown'] = $this->Departments_model->get_department_dropdown();

        return $this->template->rander("stores_inventory/create_page", $view_data);
    }

    function create() {
        return $this->create_page();
    }

    function modal_form($id = 0) {
        if (!$id) {
            $id = $this->request->getPost('id');
        }

        $view_data['model_info'] = $this->Store_inventory_model->get_one($id);

        $view_data['category_dropdown'] = array(
            "sports_gear"        => app_lang("sports_gear"),
            "engineering_spares" => app_lang("engineering_spares"),
            "ict_consumables"    => app_lang("ict_consumables"),
            "office_supplies"    => app_lang("office_supplies"),
        );

        $view_data['condition_dropdown'] = array(
            "good"      => app_lang("condition_good"),
            "fair"      => app_lang("condition_fair"),
            "defective" => app_lang("condition_defective"),
            "obsolete"  => app_lang("condition_obsolete"),
        );

        $view_data['departments_dropdown'] = $this->Departments_model->get_department_dropdown();

        return $this->template->view('stores_inventory/modal_form', $view_data);
    }

    function save_item() {
        $this->validate_submitted_data(array(
            "id"        => "numeric",
            "item_name" => "required",
            "category"  => "required",
        ));

        $id = $this->request->getPost('id');
        $sku = strtoupper(trim($this->request->getPost('sku_code')));
        if (!$sku) {
            $sku = "SKU-NCS-" . strtoupper(substr(uniqid(), -6));
        }

        $data = array(
            "sku_code"               => $sku,
            "item_name"              => $this->request->getPost('item_name'),
            "category"               => $this->request->getPost('category'),
            "unit_of_measure"        => $this->request->getPost('unit_of_measure') ?: 'Units',
            "unit_cost"              => (float)$this->request->getPost('unit_cost'),
            "quantity_on_hand"       => (int)$this->request->getPost('quantity_on_hand'),
            "min_reorder_level"      => (int)$this->request->getPost('min_reorder_level') ?: 5,
            "warehouse_bin_location" => $this->request->getPost('warehouse_bin_location') ?: 'Central Warehouse',
            "condition"              => $this->request->getPost('condition') ?: 'good',
            "department_id"          => (int)$this->request->getPost('department_id'),
            "status"                 => ((int)$this->request->getPost('quantity_on_hand') <= (int)$this->request->getPost('min_reorder_level')) ? 'low_stock' : 'in_stock',
        );

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Store_inventory_model->ci_save($data, $id);
        if ($save_id) {
            $this->Store_audit_trail_model->log_action(
                $save_id,
                $id ? "item_update" : "item_creation",
                $data["quantity_on_hand"],
                "",
                $data["warehouse_bin_location"],
                $this->login_user->id,
                "Item " . $data["item_name"] . " registered/updated."
            );
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Store_inventory_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    function list_data() {
        $options = array(
            "category"  => $this->request->getPost("category"),
            "status"    => $this->request->getPost("status"),
            "condition" => $this->request->getPost("condition"),
            "search"    => $this->request->getPost("search"),
        );
        $result = $this->Store_inventory_model->get_details($options)->getResult();

        $list_data = array();
        foreach ($result as $row) {
            $list_data[] = $this->_make_row($row);
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(array("data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found));
    }

    private function _make_row($row) {
        $status_badges = array(
            "in_stock"    => "<span class='badge bg-success'>" . app_lang("in_stock") . "</span>",
            "low_stock"   => "<span class='badge bg-warning text-dark'>" . app_lang("low_stock") . "</span>",
            "out_of_stock"=> "<span class='badge bg-danger'>" . app_lang("out_of_stock") . "</span>",
            "reissued"    => "<span class='badge bg-info text-dark'>" . app_lang("reissued") . "</span>",
        );
        $status_badge = get_array_value($status_badges, $row->status) ?: "<span class='badge bg-light text-dark'>{$row->status}</span>";

        $category_labels = array(
            "sports_gear"        => app_lang("sports_gear"),
            "engineering_spares" => app_lang("engineering_spares"),
            "ict_consumables"    => app_lang("ict_consumables"),
            "office_supplies"    => app_lang("office_supplies"),
        );
        $cat_label = get_array_value($category_labels, $row->category) ?: $row->category;

        $actions = modal_anchor(get_uri("stores_inventory/modal_form/" . $row->id), "<i data-feather='edit' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-info mr5", "title" => app_lang("edit")))
            . modal_anchor(get_uri("stores_inventory/defect_modal_form/" . $row->id), "<i data-feather='alert-circle' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-warning mr5", "title" => app_lang("report_defect")));

        return array(
            "<code>" . $row->sku_code . "</code>",
            "<strong>" . $row->item_name . "</strong>",
            "<span class='badge bg-secondary'>" . $cat_label . "</span>",
            number_format($row->quantity_on_hand) . " " . $row->unit_of_measure,
            to_currency($row->unit_cost),
            to_currency($row->total_stock_value),
            $row->warehouse_bin_location ?: "-",
            $status_badge,
            $actions
        );
    }

    function delete_item($id = 0) {
        if (!$id) {
            $id = $this->request->getPost('id');
        }
        if ($this->Store_inventory_model->delete_one($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    /* ─── GOODS RECEIVED NOTES (GRN) ────────────────────────────── */

    function grn() {
        return $this->template->rander("stores_inventory/grn/index");
    }

    function grn_list_data() {
        $search = $this->request->getPost("search");
        $result = $this->Store_grn_model->get_details(array("search" => $search))->getResult();
        $list_data = array();
        foreach ($result as $row) {
            $list_data[] = array(
                "<strong>" . $row->grn_number . "</strong>",
                $row->po_reference ?: "-",
                $row->supplier_name,
                format_to_date($row->received_date, false),
                to_currency($row->total_value),
                "<span class='badge bg-success'>" . strtoupper($row->quality_status) . "</span>",
                $row->receiver_name ?: "-"
            );
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(array("data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found));
    }

    function grn_modal_form($id = 0) {
        $view_data['model_info'] = $this->Store_grn_model->get_one($id);
        $items = $this->Store_inventory_model->get_details(array())->getResult();
        $items_dropdown = array("" => "- " . app_lang("select_item") . " -");
        foreach ($items as $it) {
            $items_dropdown[$it->id] = $it->item_name . " (" . $it->sku_code . ")";
        }
        $view_data['items_dropdown'] = $items_dropdown;
        return $this->template->view('stores_inventory/grn/modal_form', $view_data);
    }

    function save_grn() {
        $this->validate_submitted_data(array(
            "supplier_name" => "required",
            "received_date" => "required",
            "total_value"   => "required|numeric",
        ));

        $grn_number = "GRN-NCS-" . date("Ymd") . "-" . rand(100, 999);
        $item_id = (int)$this->request->getPost("item_id");
        $qty     = (int)$this->request->getPost("quantity_received");

        $data = array(
            "grn_number"        => $grn_number,
            "po_reference"      => $this->request->getPost("po_reference"),
            "supplier_name"     => $this->request->getPost("supplier_name"),
            "received_by"       => $this->login_user->id,
            "received_date"     => $this->request->getPost("received_date"),
            "items_summary"     => $this->request->getPost("notes") ?: "Inward delivery received",
            "total_value"       => (float)$this->request->getPost("total_value"),
            "quality_status"    => $this->request->getPost("quality_status") ?: "accepted",
            "delivery_note_ref" => $this->request->getPost("delivery_note_ref"),
            "notes"             => $this->request->getPost("notes"),
            "created_at"        => get_current_utc_time(),
        );

        $save_id = $this->Store_grn_model->ci_save($data);
        if ($save_id) {
            if ($item_id && $qty > 0) {
                $item = $this->Store_inventory_model->get_one($item_id);
                if ($item && $item->id) {
                    $new_qty = $item->quantity_on_hand + $qty;
                    $this->Store_inventory_model->ci_save(array(
                        "quantity_on_hand" => $new_qty,
                        "status"           => ($new_qty > $item->min_reorder_level) ? 'in_stock' : 'low_stock'
                    ), $item_id);

                    $this->Store_audit_trail_model->log_action(
                        $item_id,
                        "grn_receipt",
                        $qty,
                        $data["supplier_name"],
                        $item->warehouse_bin_location,
                        $this->login_user->id,
                        "Received " . $qty . " units via " . $grn_number
                    );
                }
            }
            echo json_encode(array("success" => true, "id" => $save_id, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── REQUISITIONS ────────────────────────────────────────────── */

    function requisitions() {
        return $this->template->rander("stores_inventory/requisitions/index");
    }

    function requisitions_list_data() {
        $search = $this->request->getPost("search");
        $result = $this->Store_requisitions_model->get_details(array("search" => $search))->getResult();
        $list_data = array();
        foreach ($result as $row) {
            $status_map = array(
                "pending_hod"  => "<span class='badge bg-warning text-dark'>" . app_lang("pending_hod") . "</span>",
                "approved_hod" => "<span class='badge bg-primary'>" . app_lang("approved_hod") . "</span>",
                "issued"       => "<span class='badge bg-success'>" . app_lang("issued") . "</span>",
                "rejected"     => "<span class='badge bg-danger'>" . app_lang("rejected") . "</span>",
            );
            $badge = get_array_value($status_map, $row->status) ?: "<span class='badge bg-secondary'>{$row->status}</span>";

            $actions = modal_anchor(get_uri("stores_inventory/approve_requisition_modal/" . $row->id), "<i data-feather='check-square' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-success mr5", "title" => app_lang("approve_reject")));

            $list_data[] = array(
                "<strong>" . $row->req_number . "</strong>",
                $row->requested_by_name ?: "-",
                $row->department_title ?: "-",
                $row->target_office ?: "-",
                $row->reason,
                format_to_date($row->requested_date, false),
                $badge,
                $actions
            );
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(array("data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found));
    }

    function requisition_modal_form($id = 0) {
        $view_data['model_info'] = $this->Store_requisitions_model->get_one($id);
        $view_data['departments_dropdown'] = $this->Departments_model->get_department_dropdown();
        return $this->template->view('stores_inventory/requisitions/modal_form', $view_data);
    }

    function save_requisition() {
        $this->validate_submitted_data(array(
            "department_id" => "required|numeric",
            "reason"        => "required",
        ));

        $req_number = "REQ-NCS-" . date("Ymd") . "-" . rand(100, 999);
        $data = array(
            "req_number"     => $req_number,
            "requested_by"   => $this->login_user->id,
            "department_id"  => (int)$this->request->getPost("department_id"),
            "target_office"  => $this->request->getPost("target_office"),
            "reason"         => $this->request->getPost("reason"),
            "status"         => "pending_hod",
            "requested_date" => get_current_utc_time(),
            "created_at"     => get_current_utc_time(),
        );

        $save_id = $this->Store_requisitions_model->ci_save($data);
        if ($save_id) {
            echo json_encode(array("success" => true, "id" => $save_id, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    function approve_requisition_modal($id = 0) {
        $view_data['model_info'] = $this->Store_requisitions_model->get_details(array("id" => $id))->getRow();
        return $this->template->view('stores_inventory/requisitions/approve_modal', $view_data);
    }

    function save_requisition_approval() {
        $id     = (int)$this->request->getPost("id");
        $status = $this->request->getPost("status");
        $data = array(
            "status"          => $status,
            "hod_approved_by" => $this->login_user->id,
            "hod_remarks"     => $this->request->getPost("hod_remarks"),
        );
        if ($this->Store_requisitions_model->ci_save($data, $id)) {
            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── ISSUANCES & RE-ISSUANCES ───────────────────────────────── */

    function issuances() {
        return $this->template->rander("stores_inventory/issuances/index");
    }

    function issuances_list_data() {
        $search = $this->request->getPost("search");
        $result = $this->Store_issuances_model->get_details(array("search" => $search))->getResult();
        $list_data = array();
        foreach ($result as $row) {
            $list_data[] = array(
                "<strong>" . $row->siv_number . "</strong>",
                "<strong>" . $row->item_name . "</strong> (" . $row->sku_code . ")",
                $row->quantity_issued,
                $row->issued_to_department_title ?: "-",
                $row->issued_to_user_name ?: "-",
                "<span class='badge bg-info text-dark'>" . ucfirst($row->condition_on_issue) . "</span>",
                $row->issued_by_name ?: "-",
                format_to_date($row->issued_date, true)
            );
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(array("data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found));
    }

    function issuance_modal_form($id = 0) {
        $view_data['model_info'] = $this->Store_issuances_model->get_one($id);

        $items = $this->Store_inventory_model->get_details(array())->getResult();
        $items_dropdown = array("" => "- " . app_lang("select_item") . " -");
        foreach ($items as $it) {
            $items_dropdown[$it->id] = $it->item_name . " (Qty: " . $it->quantity_on_hand . ")";
        }
        $view_data['items_dropdown'] = $items_dropdown;

        $view_data['departments_dropdown'] = $this->Departments_model->get_department_dropdown();

        $users = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->getResult();
        $users_dropdown = array("" => "- " . app_lang("select_user") . " -");
        foreach ($users as $u) {
            $users_dropdown[$u->id] = $u->first_name . " " . $u->last_name;
        }
        $view_data['users_dropdown'] = $users_dropdown;

        $roles = $this->Roles_model->get_details()->getResult();
        $roles_dropdown = array("" => "- " . app_lang("select_role") . " -");
        foreach ($roles as $r) {
            $roles_dropdown[$r->id] = $r->title;
        }
        $view_data['roles_dropdown'] = $roles_dropdown;

        return $this->template->view('stores_inventory/issuances/modal_form', $view_data);
    }

    function save_issuance() {
        $this->validate_submitted_data(array(
            "item_id"                 => "required|numeric",
            "quantity_issued"         => "required|numeric",
            "issued_to_department_id" => "required|numeric",
        ));

        $item_id = (int)$this->request->getPost("item_id");
        $qty     = (int)$this->request->getPost("quantity_issued");
        $item    = $this->Store_inventory_model->get_one($item_id);

        if (!$item || $item->quantity_on_hand < $qty) {
            echo json_encode(array("success" => false, "message" => app_lang("insufficient_stock_available")));
            return;
        }

        $siv_number = "SIV-NCS-" . date("Ymd") . "-" . rand(100, 999);
        $data = array(
            "siv_number"              => $siv_number,
            "item_id"                 => $item_id,
            "quantity_issued"         => $qty,
            "issued_to_department_id" => (int)$this->request->getPost("issued_to_department_id"),
            "issued_to_role_id"       => (int)$this->request->getPost("issued_to_role_id"),
            "issued_to_user_id"       => (int)$this->request->getPost("issued_to_user_id"),
            "condition_on_issue"      => $this->request->getPost("condition_on_issue") ?: "good",
            "issued_by"               => $this->login_user->id,
            "issued_date"             => get_current_utc_time(),
            "handover_notes"          => $this->request->getPost("handover_notes"),
        );

        $save_id = $this->Store_issuances_model->ci_save($data);
        if ($save_id) {
            $new_qty = $item->quantity_on_hand - $qty;
            $this->Store_inventory_model->ci_save(array(
                "quantity_on_hand" => $new_qty,
                "status"           => ($new_qty <= 0) ? 'out_of_stock' : (($new_qty <= $item->min_reorder_level) ? 'low_stock' : 'in_stock')
            ), $item_id);

            $this->Store_audit_trail_model->log_action(
                $item_id,
                "stock_issuance",
                $qty,
                $item->warehouse_bin_location,
                "Issued to Department ID: " . $data["issued_to_department_id"],
                $this->login_user->id,
                "Issued " . $qty . " units under " . $siv_number
            );

            echo json_encode(array("success" => true, "id" => $save_id, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── STOCK TAKES & ACCOUNTING RECONCILIATION ────────────────── */

    function stock_takes() {
        return $this->template->rander("stores_inventory/stock_takes/index");
    }

    function stock_takes_list_data() {
        $search = $this->request->getPost("search");
        $result = $this->Store_stock_takes_model->get_details(array("search" => $search))->getResult();
        $list_data = array();
        foreach ($result as $row) {
            $var_color = ($row->variance == 0) ? "success" : (($row->variance > 0) ? "primary" : "danger");
            $list_data[] = array(
                format_to_date($row->take_date, false),
                "<strong>" . $row->item_name . "</strong> (" . $row->sku_code . ")",
                $row->book_qty,
                $row->physical_qty,
                "<span class='badge bg-{$var_color}'>" . ($row->variance > 0 ? "+" . $row->variance : $row->variance) . "</span>",
                to_currency($row->total_variance_value),
                $row->inspector_name ?: "-",
                $row->reconciliation_notes ?: "-"
            );
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(array("data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found));
    }

    function stock_take_modal_form($id = 0) {
        $items = $this->Store_inventory_model->get_details(array())->getResult();
        $items_dropdown = array("" => "- " . app_lang("select_item") . " -");
        foreach ($items as $it) {
            $items_dropdown[$it->id] = $it->item_name . " (Book Qty: " . $it->quantity_on_hand . ")";
        }
        $view_data['items_dropdown'] = $items_dropdown;
        return $this->template->view('stores_inventory/stock_takes/modal_form', $view_data);
    }

    function save_stock_take() {
        $this->validate_submitted_data(array(
            "item_id"      => "required|numeric",
            "physical_qty" => "required|numeric",
        ));

        $item_id  = (int)$this->request->getPost("item_id");
        $physical = (int)$this->request->getPost("physical_qty");
        $item     = $this->Store_inventory_model->get_one($item_id);

        $book_qty = $item->quantity_on_hand;
        $variance = $physical - $book_qty;

        $data = array(
            "take_date"            => date("Y-m-d"),
            "conducted_by"         => $this->login_user->id,
            "item_id"              => $item_id,
            "book_qty"             => $book_qty,
            "physical_qty"         => $physical,
            "variance"             => $variance,
            "reconciliation_notes" => $this->request->getPost("reconciliation_notes"),
            "status"               => "completed",
            "created_at"           => get_current_utc_time(),
        );

        $save_id = $this->Store_stock_takes_model->ci_save($data);
        if ($save_id) {
            // Adjust book quantity to match physical count
            $this->Store_inventory_model->ci_save(array(
                "quantity_on_hand" => $physical,
                "status"           => ($physical <= 0) ? 'out_of_stock' : (($physical <= $item->min_reorder_level) ? 'low_stock' : 'in_stock')
            ), $item_id);

            $this->Store_audit_trail_model->log_action(
                $item_id,
                "stock_take_adjustment",
                $variance,
                "Book: " . $book_qty,
                "Physical: " . $physical,
                $this->login_user->id,
                "Stock count variance adjusted. Notes: " . $data["reconciliation_notes"]
            );

            echo json_encode(array("success" => true, "id" => $save_id, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── DEFECT & OBSOLESCENCE FLAGGING ──────────────────────────── */

    function obsolescence() {
        return $this->template->rander("stores_inventory/obsolescence/index");
    }

    function defect_modal_form($id = 0) {
        $view_data['model_info'] = $this->Store_inventory_model->get_one($id);
        $items = $this->Store_inventory_model->get_details(array())->getResult();
        $items_dropdown = array("" => "- " . app_lang("select_item") . " -");
        foreach ($items as $it) {
            $items_dropdown[$it->id] = $it->item_name . " (" . $it->sku_code . ")";
        }
        $view_data['items_dropdown'] = $items_dropdown;
        return $this->template->view('stores_inventory/obsolescence/modal_form', $view_data);
    }

    function save_defect_report() {
        $item_id   = (int)$this->request->getPost("item_id");
        $condition = $this->request->getPost("condition");
        $details   = $this->request->getPost("details");

        $item = $this->Store_inventory_model->get_one($item_id);
        if ($item && $item->id) {
            $this->Store_inventory_model->ci_save(array("condition" => $condition), $item_id);

            $this->Store_audit_trail_model->log_action(
                $item_id,
                "defect_obsolescence_report",
                0,
                $item->condition,
                $condition,
                $this->login_user->id,
                "Condition updated to " . strtoupper($condition) . ". Details: " . $details
            );

            echo json_encode(array("success" => true, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── CATEGORY SPECIFIC VIEWS ────────────────────────────────── */

    function sports_gear() {
        $view_data['category'] = 'sports_gear';
        $view_data['title']    = app_lang("sports_equipment_pool");
        return $this->template->rander("stores_inventory/sports_gear/index", $view_data);
    }

    function engineering_spares() {
        $view_data['category'] = 'engineering_spares';
        $view_data['title']    = app_lang("engineering_spares");
        return $this->template->rander("stores_inventory/sports_gear/index", $view_data);
    }

    function ict_consumables() {
        $view_data['category'] = 'ict_consumables';
        $view_data['title']    = app_lang("ict_consumables");
        return $this->template->rander("stores_inventory/sports_gear/index", $view_data);
    }

    function office_supplies() {
        $view_data['category'] = 'office_supplies';
        $view_data['title']    = app_lang("office_supplies");
        return $this->template->rander("stores_inventory/sports_gear/index", $view_data);
    }

    /* ─── AUDIT TRAIL ────────────────────────────────────────────── */

    function audit_trail() {
        return $this->template->rander("stores_inventory/audit_trail/index");
    }

    function audit_trail_list_data() {
        $search = $this->request->getPost("search");
        $result = $this->Store_audit_trail_model->get_details(array("search" => $search))->getResult();
        $list_data = array();
        foreach ($result as $row) {
            $list_data[] = array(
                format_to_date($row->created_at, true),
                "<strong>" . ($row->item_name ?: "System") . "</strong>",
                "<span class='badge bg-info text-dark'>" . strtoupper($row->action_type) . "</span>",
                $row->quantity,
                $row->from_location ?: "-",
                $row->to_location ?: "-",
                $row->reporter_name ?: "-",
                $row->details
            );
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(array("data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found));
    }

    /* ─── ALERTS ──────────────────────────────────────────────────── */

    function alerts() {
        $view_data['low_stock_items'] = $this->Store_inventory_model->get_details(array("low_stock" => true))->getResult();
        return $this->template->rander("stores_inventory/alerts/index", $view_data);
    }

    /* ─── REPORTS & INTERNAL AUDIT ────────────────────────────────── */

    function reports() {
        $view_data['category_summary'] = $this->Store_inventory_model->get_category_summary();
        $view_data['all_items']        = $this->Store_inventory_model->get_details(array())->getResult();
        $view_data['stock_takes']      = $this->Store_stock_takes_model->get_details(array())->getResult();
        return $this->template->rander("stores_inventory/reports/index", $view_data);
    }
}
