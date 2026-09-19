<?php

namespace App\Controllers;

use App\Libraries\Pdf;

class Visitor_logbook extends Security_Controller {

    protected $Visitor_logbook_model;

    function __construct() {
        parent::__construct();
        $this->Visitor_logbook_model = model('App\Models\Visitor_logbook_model');
    }

    // Main Logbook Page View
    function index() {
        return $this->template->rander("visitor_logbook/index");
    }

    // Datatable Source
    function list_data() {
        $options = array(
            "status" => $this->request->getPost("status"),
            "nationality" => $this->request->getPost("nationality"),
            "gate_name" => $this->request->getPost("gate_name"),
            "start_date" => $this->request->getPost("start_date"),
            "end_date" => $this->request->getPost("end_date")
        );

        $list_data = $this->Visitor_logbook_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    // Format Datatable Row
    private function _make_row($data) {
        $visitor_name = esc($data->first_name . " " . $data->last_name . ($data->other_name ? " " . $data->other_name : ""));
        $nationality_badge = $data->nationality === 'ugandan'
            ? "<span class='badge bg-info'>" . app_lang('ugandan') . "</span>"
            : "<span class='badge bg-secondary'>" . app_lang('non_ugandan') . "</span>";

        $status_class = $data->status === "checked_in" ? "bg-success" : "bg-dark";
        $status_label = "<span class='badge $status_class'>" . app_lang($data->status) . "</span>";

        $time_in_formatted = format_to_datetime($data->time_in);
        $time_out_formatted = $data->time_out ? format_to_datetime($data->time_out) : "-";

        $actions = modal_anchor(get_uri("visitor_logbook/view/" . $data->id), "<i data-feather='eye' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('visitor_records'), "data-post-id" => $data->id));

        if ($data->status === "checked_in") {
            $actions .= js_anchor("<i data-feather='log-out' class='icon-16'></i>", array('title' => app_lang('check_out'), "class" => "edit text-warning", "data-id" => $data->id, "data-action-url" => get_uri("visitor_logbook/check_out"), "data-action" => "delete", "data-undo" => "0", "data-custom-message" => "Confirm Check Out for " . $visitor_name . "?"));
        }

        $actions .= anchor(get_uri("visitor_logbook/download_pdf/" . $data->id), "<i data-feather='download' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('print_pdf'), "target" => "_blank"));

        if ($this->login_user->is_admin || $this->login_user->id == $data->recorded_by) {
            $actions .= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("visitor_logbook/delete"), "data-action" => "delete"));
        }

        return array(
            $data->id,
            $visitor_name . "<br />" . $nationality_badge . " <small class='text-off'>" . strtoupper($data->id_type) . ": " . esc($data->id_number) . "</small>",
            esc($data->phone ? $data->phone : "-"),
            esc($data->organization ? $data->organization : "-"),
            esc($data->to_user_name ? $data->to_user_name : "-"),
            esc($data->gate_name),
            $time_in_formatted,
            $time_out_formatted,
            $status_label,
            $actions
        );
    }

    // Modal Form for Visitor Check-In
    function modal_form() {
        $id = $this->request->getPost('id');
        $model_info = $this->Visitor_logbook_model->get_one($id);

        $view_data['model_info'] = $model_info;
        $view_data['gates_dropdown'] = $this->_get_gates_dropdown();
        $view_data['team_members_dropdown'] = $this->_get_team_members_dropdown();

        return $this->template->view('visitor_logbook/modal_form', $view_data);
    }

    // Direct Create Page
    function create() {
        $view_data['gates_dropdown'] = $this->_get_gates_dropdown();
        $view_data['team_members_dropdown'] = $this->_get_team_members_dropdown();
        return $this->template->rander("visitor_logbook/create_page", $view_data);
    }

    // Customizable Entry Gates List
    private function _get_gates_dropdown() {
        return array(
            "Main Gate - Lugogo" => "Main Gate - Lugogo",
            "VIP Gate - Head Office" => "VIP Gate - Head Office",
            "Stadium Gate" => "Stadium Gate - Indoor Arena",
            "Tennis Complex Gate" => "Tennis Complex Gate",
            "Hostel Gate" => "Hostel Gate"
        );
    }

    // Searchable Team Members Dropdown by Name, Email, and Role
    private function _get_team_members_dropdown() {
        $db = \Config\Database::connect();
        $users_table = $db->prefixTable('users');
        $roles_table = $db->prefixTable('roles');

        $sql = "SELECT $users_table.id, $users_table.first_name, $users_table.last_name, $users_table.email, $users_table.job_title, $roles_table.title AS role_title
                FROM $users_table
                LEFT JOIN $roles_table ON $roles_table.id = $users_table.role_id
                WHERE $users_table.deleted=0 AND $users_table.user_type='staff' AND $users_table.status='active'
                ORDER BY $users_table.first_name ASC";

        $users = $db->query($sql)->getResult();
        $dropdown = array("" => "- " . app_lang('person_to_visit') . " -");

        foreach ($users as $user) {
            $role_text = $user->job_title ? $user->job_title : ($user->role_title ? $user->role_title : "Staff");
            $label = $user->first_name . " " . $user->last_name . " (" . $user->email . ") - [" . $role_text . "]";
            $dropdown[$user->id] = $label;
        }

        return $dropdown;
    }

    // Save Visitor Log Record
    function save() {
        $id = $this->request->getPost('id');
        $nationality = $this->request->getPost('nationality');
        $first_name = $this->request->getPost('first_name');
        $last_name = $this->request->getPost('last_name');
        $other_name = $this->request->getPost('other_name');
        $id_type = $this->request->getPost('id_type');
        $id_number = $this->request->getPost('id_number');
        $phone = $this->request->getPost('phone');
        $email = $this->request->getPost('email');
        $organization = $this->request->getPost('organization');
        $to_user_id = $this->request->getPost('to_user_id');
        $gate_name = $this->request->getPost('gate_name');
        $reason = $this->request->getPost('reason');

        $this->validate_submitted_data(array(
            "first_name" => "required",
            "last_name" => "required",
            "id_number" => "required",
            "to_user_id" => "required|numeric",
            "reason" => "required"
        ));

        // Enforce NIN for Ugandans
        if ($nationality === "ugandan") {
            $id_type = "nin";
        }

        $data = array(
            "nationality" => $nationality ? $nationality : "ugandan",
            "first_name" => $first_name,
            "last_name" => $last_name,
            "other_name" => $other_name,
            "id_type" => $id_type ? $id_type : "nin",
            "id_number" => $id_number,
            "phone" => $phone,
            "email" => $email,
            "organization" => $organization,
            "to_user_id" => $to_user_id,
            "gate_name" => $gate_name ? $gate_name : "Main Gate - Lugogo",
            "reason" => $reason,
            "updated_at" => get_current_utc_time()
        );

        if (!$id) {
            $data["recorded_by"] = $this->login_user->id;
            $data["time_in"] = get_current_utc_time();
            $data["status"] = "checked_in";
            $data["created_at"] = get_current_utc_time();
        }

        // Handle File Attachments
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "visitor_logbook");
        $new_files = unserialize($files_data);

        if ($id) {
            $log_info = $this->Visitor_logbook_model->get_one($id);
            $new_files = update_saved_files($target_path, $log_info->files, $new_files);
        }

        $data["files"] = serialize($new_files);

        $save_id = $this->Visitor_logbook_model->ci_save($data, $id);

        if ($save_id) {
            // Send Internal Message Notification to host officer
            if (!$id) {
                $visitor_display_name = $first_name . " " . $last_name . ($organization ? " (" . $organization . ")" : "");
                $msg_body = "Hello,\n\nA visitor has arrived at " . $data["gate_name"] . " to see you.\n\nVisitor Name: " . $visitor_display_name . "\nID Number: " . strtoupper($data["id_type"]) . " - " . $id_number . "\nReason for Visit: " . $reason . "\nTime In: " . format_to_datetime($data["time_in"]) . "\n\nPlease be prepared for their arrival.";

                $message_data = array(
                    "to_user_id" => $to_user_id,
                    "from_user_id" => $this->login_user->id,
                    "subject" => "Visitor Arrival Alert at " . $data["gate_name"] . ": " . $visitor_display_name,
                    "message" => $msg_body,
                    "files" => "",
                    "deleted_by_users" => "",
                    "created_at" => get_current_utc_time()
                );

                $this->Messages_model->ci_save($message_data);
            }

            echo json_encode(array("success" => true, "id" => $save_id, "data" => $this->_make_row($this->Visitor_logbook_model->get_details(array("id" => $save_id))->getRow()), "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    // 1-Click Visitor Check-Out Action
    function check_out() {
        $id = $this->request->getPost('id');
        if (!$id) {
            show_404();
        }

        $data = array(
            "status" => "checked_out",
            "time_out" => get_current_utc_time(),
            "checked_out_by" => $this->login_user->id,
            "updated_at" => get_current_utc_time()
        );

        $save_id = $this->Visitor_logbook_model->ci_save($data, $id);

        if ($save_id) {
            $updated_info = $this->Visitor_logbook_model->get_details(array("id" => $id))->getRow();
            echo json_encode(array("success" => true, "id" => $save_id, "data" => $this->_make_row($updated_info), "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    // View Details Modal
    function view($id = 0) {
        if (!$id) {
            show_404();
        }

        $options = array("id" => $id);
        $log_info = $this->Visitor_logbook_model->get_details($options)->getRow();

        if (!$log_info) {
            show_404();
        }

        $view_data['model_info'] = $log_info;
        return $this->template->view('visitor_logbook/view_modal', $view_data);
    }

    // Delete Record
    function delete() {
        $id = $this->request->getPost('id');
        if ($this->request->getPost('undo')) {
            if ($this->Visitor_logbook_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_make_row($this->Visitor_logbook_model->get_details(array("id" => $id))->getRow()), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
            }
        } else {
            if ($this->Visitor_logbook_model->delete($id)) {
                echo json_encode(array("success" => true, 'id' => $id, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
            }
        }
    }

    // Download/Print Gate Pass PDF
    function download_pdf($id = 0) {
        if (!$id) {
            show_404();
        }

        $log_info = $this->Visitor_logbook_model->get_details(array("id" => $id))->getRow();

        if (!$log_info) {
            show_404();
        }

        $view_data['model_info'] = $log_info;
        $html = view("visitor_logbook/pdf", $view_data);

        $pdf = new Pdf();
        $pdf->PreparePDF($html, "Visitor_GatePass_" . $id . ".pdf", "download");
    }
}
