<?php

namespace App\Controllers;

class Departments extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->access_only_admin_or_settings_admin();
    }

    function index() {
        return $this->template->rander("departments/index");
    }

    function create() {
        return $this->modal_form(0);
    }

    function list_data() {
        $search = $this->request->getPost("search");
        $options = array("search" => $search);
        $list_data = $this->Departments_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_row($data) {
        $actions = modal_anchor(get_uri("departments/modal_form/" . $data->id), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit btn btn-sm btn-outline-info mr5", "title" => app_lang('edit_department')))
            . js_anchor("<i data-feather='trash-2' class='icon-16'></i>", array("title" => app_lang('delete_department'), "class" => "delete btn btn-sm btn-outline-danger", "data-id" => $data->id, "data-action-url" => get_uri("departments/delete/" . $data->id), "data-action" => "delete-confirmation"));

        $code_badge = $data->code ? "<span class='badge bg-primary mr5'>{$data->code}</span>" : "";

        return array(
            $code_badge . "<strong>" . $data->title . "</strong>",
            $data->description ?: "-",
            $data->head_name ?: "-",
            "<span class='badge bg-secondary'>" . $data->roles_count . " " . app_lang("roles") . "</span>",
            $actions
        );
    }

    function modal_form($id = 0) {
        if (!$id) {
            $id = $this->request->getPost('id');
        }

        $view_data['model_info'] = $this->Departments_model->get_one($id);

        $users = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->getResult();
        $staff_dropdown = array("" => "- " . app_lang("select_department_head") . " -");
        foreach ($users as $u) {
            $staff_dropdown[$u->id] = $u->first_name . " " . $u->last_name;
        }
        $view_data['staff_dropdown'] = $staff_dropdown;

        return $this->template->view('departments/modal_form', $view_data);
    }

    function save() {
        $this->validate_submitted_data(array(
            "id"    => "numeric",
            "title" => "required"
        ));

        $id = $this->request->getPost('id');
        $data = array(
            "title"       => $this->request->getPost('title'),
            "code"        => strtoupper(trim($this->request->getPost('code'))),
            "description" => $this->request->getPost('description'),
            "head_id"     => (int)$this->request->getPost('head_id')
        );

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Departments_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Departments_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    function delete($id = 0) {
        if (!$id) {
            $id = $this->request->getPost('id');
        }
        if ($this->Departments_model->delete_one($id)) {
            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }
}
