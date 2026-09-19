<?php

namespace App\Controllers;

use App\Models\Suppliers_model;
use App\Models\Supplier_contacts_model;

class Suppliers extends Security_Controller {

    protected $Suppliers_model;
    protected $Supplier_contacts_model;

    function __construct() {
        parent::__construct();
        $this->Suppliers_model = new Suppliers_model();
        $this->Supplier_contacts_model = new Supplier_contacts_model();
    }

    function index($tab = "") {
        $view_data['tab'] = clean_data($tab);
        $view_data['stats'] = $this->Suppliers_model->get_supplier_stats();
        return $this->template->rander("suppliers/index", $view_data);
    }

    function list_data() {
        $category = $this->request->getPost("category");
        $prequalification_status = $this->request->getPost("prequalification_status");

        $options = array(
            "category" => $category,
            "prequalification_status" => $prequalification_status
        );

        $list_data = $this->Suppliers_model->get_details($options)->getResult();
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $company_name = anchor(get_uri("suppliers/view/" . $data->id), "<strong>" . $data->company_name . "</strong>") . 
                        "<br/><small class='text-muted'>" . ($data->supplier_code ?: 'N/A') . " • " . ($data->city ?: 'Kampala') . ", " . ($data->country ?: 'Uganda') . "</small>";

        $category = "<span class='badge bg-soft-info text-info'>" . esc($data->category) . "</span>";

        $compliance = "<div><small><strong>PPDA:</strong> " . esc($data->ppda_registration_no ?: 'N/A') . "</small></div>" .
                      "<div><small><strong>TIN:</strong> " . esc($data->tin_number ?: 'N/A') . "</small></div>";

        $status = "<span class='badge bg-success'><i data-feather='check-circle' class='icon-14'></i> PRE-QUALIFIED</span>";
        if ($data->is_blacklisted) {
            $status = "<span class='badge bg-danger'><i data-feather='alert-triangle' class='icon-14'></i> BLACKLISTED</span>";
        } else if ($data->prequalification_status == "PROVISIONAL") {
            $status = "<span class='badge bg-warning text-dark'><i data-feather='clock' class='icon-14'></i> PROVISIONAL</span>";
        } else if ($data->prequalification_status == "PENDING") {
            $status = "<span class='badge bg-secondary'><i data-feather='help-circle' class='icon-14'></i> PENDING</span>";
        }

        $contact_info = "";
        if ($data->primary_contact_name) {
            $contact_info = "<div><i data-feather='user' class='icon-14 text-muted'></i> " . esc($data->primary_contact_name) . "</div>" .
                            "<div><i data-feather='mail' class='icon-14 text-muted'></i> " . esc($data->primary_contact_email ?: $data->email) . "</div>" .
                            "<div><i data-feather='phone' class='icon-14 text-muted'></i> " . esc($data->primary_contact_phone ?: $data->phone) . "</div>";
        } else {
            $contact_info = "<div><i data-feather='mail' class='icon-14 text-muted'></i> " . esc($data->email ?: 'N/A') . "</div>" .
                            "<div><i data-feather='phone' class='icon-14 text-muted'></i> " . esc($data->phone ?: 'N/A') . "</div>";
        }

        $rating_stars = "<span class='text-warning'><i data-feather='star' class='icon-14 fill-warning'></i> " . number_format($data->rating, 1) . " / 5.0</span>";

        $actions = anchor(get_uri("suppliers/view/" . $data->id), "<i data-feather='eye' class='icon-16'></i>", array("class" => "edit btn btn-sm btn-outline-info mr5", "title" => "View Supplier Profile")) .
                   modal_anchor(get_uri("suppliers/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit btn btn-sm btn-outline-primary mr5", "title" => "Edit Supplier", "data-post-id" => $data->id)) .
                   js_anchor("<i data-feather='trash-2' class='icon-16'></i>", array("title" => "Delete Supplier", "class" => "delete btn btn-sm btn-outline-danger", "data-id" => $data->id, "data-action-url" => get_uri("suppliers/delete"), "data-action" => "delete-confirmation"));

        return array(
            $data->id,
            $company_name,
            $category,
            $compliance,
            $contact_info,
            $rating_stars,
            $status,
            $actions
        );
    }

    function view($id = 0) {
        validate_numeric_value($id);
        $model_info = $this->Suppliers_model->get_details(array("id" => $id))->getRow();

        if (!$model_info) {
            show_404();
        }

        $view_data['model_info'] = $model_info;
        $view_data['contacts'] = $this->Supplier_contacts_model->get_details(array("supplier_id" => $id))->getResult();

        return $this->template->rander("suppliers/view", $view_data);
    }

    function modal_form() {
        $id = $this->request->getPost('id');
        validate_numeric_value($id);

        $view_data['model_info'] = $this->Suppliers_model->get_one($id);
        return view('suppliers/supplier_modal_form', $view_data);
    }

    function save() {
        $id = $this->request->getPost('id');
        validate_numeric_value($id);

        $company_name = $this->request->getPost('company_name');
        if (!$company_name) {
            echo json_encode(array("success" => false, 'message' => "Company Name is required"));
            return;
        }

        $data = array(
            "company_name" => $company_name,
            "supplier_code" => $this->request->getPost('supplier_code') ?: ('SUP-' . date('Y') . '-' . rand(100, 999)),
            "category" => $this->request->getPost('category'),
            "ppda_registration_no" => $this->request->getPost('ppda_registration_no'),
            "tin_number" => $this->request->getPost('tin_number'),
            "vat_number" => $this->request->getPost('vat_number'),
            "address" => $this->request->getPost('address'),
            "city" => $this->request->getPost('city') ?: 'Kampala',
            "country" => $this->request->getPost('country') ?: 'Uganda',
            "website" => $this->request->getPost('website'),
            "phone" => $this->request->getPost('phone'),
            "email" => $this->request->getPost('email'),
            "prequalification_status" => $this->request->getPost('prequalification_status') ?: 'PRE_QUALIFIED',
            "is_blacklisted" => $this->request->getPost('is_blacklisted') ? true : false,
            "blacklist_reason" => $this->request->getPost('blacklist_reason'),
            "rating" => unformat_currency($this->request->getPost('rating')) ?: 4.50,
            "payment_terms" => $this->request->getPost('payment_terms') ?: 'Net 30 Days',
            "bank_name" => $this->request->getPost('bank_name'),
            "bank_account_no" => $this->request->getPost('bank_account_no'),
            "bank_branch" => $this->request->getPost('bank_branch')
        );

        $save_id = $this->Suppliers_model->ci_save($data, $id);

        if ($save_id) {
            echo json_encode(array("success" => true, "id" => $save_id, 'data' => $this->_make_row($this->Suppliers_model->get_details(array("id" => $save_id))->getRow()), 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function delete() {
        $id = $this->request->getPost('id');
        validate_numeric_value($id);

        if ($this->Suppliers_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* Supplier Contacts Directory */

    function contacts() {
        $view_data['stats'] = $this->Suppliers_model->get_supplier_stats();
        return $this->template->rander("suppliers/contacts", $view_data);
    }

    function contacts_list_data() {
        $supplier_id = $this->request->getPost("supplier_id");
        $options = array("supplier_id" => $supplier_id);

        $list_data = $this->Supplier_contacts_model->get_details($options)->getResult();
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_contact_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    private function _make_contact_row($data) {
        $name = "<strong>" . esc($data->first_name . " " . $data->last_name) . "</strong>";
        if ($data->is_primary_contact) {
            $name .= " <span class='badge bg-primary ms-1'>PRIMARY CONTACT</span>";
        }

        $company = anchor(get_uri("suppliers/view/" . $data->supplier_id), esc($data->supplier_company_name ?: 'Supplier #' . $data->supplier_id)) .
                   "<br/><small class='text-muted'>" . esc($data->supplier_category ?: 'Vendor') . "</small>";

        $job_title = esc($data->job_title ?: 'Representative');

        $email = "<a href='mailto:" . esc($data->email) . "'><i data-feather='mail' class='icon-14'></i> " . esc($data->email) . "</a>";

        $phone = "<div><i data-feather='phone' class='icon-14'></i> " . esc($data->phone ?: 'N/A') . "</div>";
        if ($data->alternative_phone) {
            $phone .= "<div><small class='text-muted'><i data-feather='phone-call' class='icon-14'></i> " . esc($data->alternative_phone) . "</small></div>";
        }

        $actions = modal_anchor(get_uri("suppliers/contact_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit btn btn-sm btn-outline-primary mr5", "title" => "Edit Contact", "data-post-id" => $data->id, "data-post-supplier_id" => $data->supplier_id)) .
                   js_anchor("<i data-feather='trash-2' class='icon-16'></i>", array("title" => "Delete Contact", "class" => "delete btn btn-sm btn-outline-danger", "data-id" => $data->id, "data-action-url" => get_uri("suppliers/delete_contact"), "data-action" => "delete-confirmation"));

        return array(
            $data->id,
            $name,
            $company,
            $job_title,
            $email,
            $phone,
            $actions
        );
    }

    function contact_modal_form() {
        $id = $this->request->getPost('id');
        $supplier_id = $this->request->getPost('supplier_id');
        validate_numeric_value($id);
        validate_numeric_value($supplier_id);

        $view_data['model_info'] = $this->Supplier_contacts_model->get_one($id);
        $view_data['supplier_id'] = $supplier_id ?: $view_data['model_info']->supplier_id;
        $view_data['suppliers_dropdown'] = $this->_get_suppliers_dropdown();

        return view('suppliers/contact_modal_form', $view_data);
    }

    private function _get_suppliers_dropdown() {
        $suppliers = $this->Suppliers_model->get_details()->getResult();
        $dropdown = array("" => "- Select Supplier Company -");
        foreach ($suppliers as $s) {
            $dropdown[$s->id] = $s->company_name . " (" . $s->category . ")";
        }
        return $dropdown;
    }

    function save_contact() {
        $id = $this->request->getPost('id');
        validate_numeric_value($id);

        $supplier_id = $this->request->getPost('supplier_id');
        $first_name = $this->request->getPost('first_name');
        $last_name = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');

        if (!$supplier_id || !$first_name || !$email) {
            echo json_encode(array("success" => false, 'message' => "Supplier, First Name, and Email are required"));
            return;
        }

        $data = array(
            "supplier_id" => $supplier_id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "job_title" => $this->request->getPost('job_title'),
            "email" => $email,
            "phone" => $this->request->getPost('phone'),
            "alternative_phone" => $this->request->getPost('alternative_phone'),
            "is_primary_contact" => $this->request->getPost('is_primary_contact') ? true : false,
            "notes" => $this->request->getPost('notes')
        );

        $save_id = $this->Supplier_contacts_model->ci_save($data, $id);

        if ($save_id) {
            echo json_encode(array("success" => true, "id" => $save_id, 'data' => $this->_make_contact_row($this->Supplier_contacts_model->get_details(array("id" => $save_id))->getRow()), 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function delete_contact() {
        $id = $this->request->getPost('id');
        validate_numeric_value($id);

        if ($this->Supplier_contacts_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }

    /* Statutory & PPDA Compliance */

    function compliance() {
        $view_data['stats'] = $this->Suppliers_model->get_supplier_stats();
        $view_data['suppliers'] = $this->Suppliers_model->get_details()->getResult();
        return $this->template->rander("suppliers/compliance", $view_data);
    }

    /* Categories & Prequalification */

    function categories() {
        $view_data['stats'] = $this->Suppliers_model->get_supplier_stats();
        $view_data['suppliers'] = $this->Suppliers_model->get_details()->getResult();
        return $this->template->rander("suppliers/categories", $view_data);
    }

    /* Vendor Performance Rating Scorecards */

    function performance() {
        $view_data['stats'] = $this->Suppliers_model->get_supplier_stats();
        $view_data['suppliers'] = $this->Suppliers_model->get_details()->getResult();
        return $this->template->rander("suppliers/performance", $view_data);
    }
}
