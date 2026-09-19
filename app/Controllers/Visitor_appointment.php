<?php

namespace App\Controllers;

use App\Libraries\Pdf;

class Visitor_appointment extends Security_Controller {

    protected $Visitor_appointments_model;

    function __construct() {
        parent::__construct();
        $this->Visitor_appointments_model = model('App\Models\Visitor_appointments_model');
    }

    // Main Appointments List View
    function index() {
        return $this->template->rander("visitor_appointment/index");
    }

    // Datatable Data Endpoint
    function list_data() {
        $options = array(
            "status" => $this->request->getPost("status"),
            "appointment_type" => $this->request->getPost("appointment_type"),
            "start_date" => $this->request->getPost("start_date"),
            "end_date" => $this->request->getPost("end_date")
        );

        // If not admin, filter to appointments where user is host or creator
        if (!$this->login_user->is_admin) {
            $options["to_user_id"] = $this->login_user->id;
        }

        $list_data = $this->Visitor_appointments_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }

        echo json_encode(array("data" => $result));
    }

    // Format single datatable row
    private function _make_row($data) {
        $visitor_name = $data->appointment_type === 'internal' 
            ? "<span class='badge bg-info'>" . app_lang('internal_staff') . "</span> " . esc($data->created_by_user)
            : esc($data->first_name . " " . $data->last_name . ($data->other_name ? " " . $data->other_name : ""));

        $status_class = "bg-warning";
        if ($data->status === "approved") {
            $status_class = "bg-success";
        } else if ($data->status === "rejected") {
            $status_class = "bg-danger";
        } else if ($data->status === "rescheduled") {
            $status_class = "bg-primary";
        }

        $status_label = "<span class='badge $status_class'>" . app_lang($data->status) . "</span>";

        $actions = modal_anchor(get_uri("visitor_appointment/view/" . $data->id), "<i data-feather='eye' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('appointment_details'), "data-post-id" => $data->id))
            . modal_anchor(get_uri("visitor_appointment/status_modal/" . $data->id), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('update_status'), "data-post-id" => $data->id))
            . anchor(get_uri("visitor_appointment/download_pdf/" . $data->id), "<i data-feather='download' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('print_pdf'), "target" => "_blank"));

        if ($this->login_user->is_admin || $this->login_user->id == $data->created_by) {
            $actions .= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("visitor_appointment/delete"), "data-action" => "delete"));
        }

        return array(
            $data->id,
            $visitor_name,
            esc($data->phone ? $data->phone : "-"),
            esc($data->organization ? $data->organization : "-"),
            esc($data->to_user_name ? $data->to_user_name : "-"),
            format_to_date($data->appointment_date, false) . " " . $data->appointment_time,
            $status_label,
            $actions
        );
    }

    // Modal Form for Creating/Editing Appointment
    function modal_form() {
        $id = $this->request->getPost('id');
        $model_info = $this->Visitor_appointments_model->get_one($id);

        $view_data['model_info'] = $model_info;
        $view_data['team_members_dropdown'] = $this->_get_team_members_dropdown();
        
        return $this->template->view('visitor_appointment/modal_form', $view_data);
    }

    // Convenient Direct Create Page
    function create() {
        $view_data['team_members_dropdown'] = $this->_get_team_members_dropdown();
        return $this->template->rander("visitor_appointment/create_page", $view_data);
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

    // Save Appointment
    function save() {
        $id = $this->request->getPost('id');
        $appointment_type = $this->request->getPost('appointment_type');
        $to_user_id = $this->request->getPost('to_user_id');
        $appointment_date = $this->request->getPost('appointment_date');
        $appointment_time = $this->request->getPost('appointment_time');
        $reason = $this->request->getPost('reason');

        $this->validate_submitted_data(array(
            "to_user_id" => "required|numeric",
            "appointment_date" => "required",
            "appointment_time" => "required",
            "reason" => "required"
        ));

        $data = array(
            "appointment_type" => $appointment_type ? $appointment_type : "external",
            "to_user_id" => $to_user_id,
            "appointment_date" => $appointment_date,
            "appointment_time" => $appointment_time,
            "reason" => $reason,
            "updated_at" => get_current_utc_time()
        );

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
            $data["status"] = "pending";
        }

        if ($data["appointment_type"] === "internal") {
            $data["first_name"] = $this->login_user->first_name;
            $data["last_name"] = $this->login_user->last_name;
            $data["email"] = $this->login_user->email;
            $data["phone"] = isset($this->login_user->phone) ? $this->login_user->phone : "";
            $data["organization"] = "National Council Of Sports";
        } else {
            $data["first_name"] = $this->request->getPost('first_name');
            $data["last_name"] = $this->request->getPost('last_name');
            $data["other_name"] = $this->request->getPost('other_name');
            $data["id_type"] = $this->request->getPost('id_type');
            $data["id_number"] = $this->request->getPost('id_number');
            $data["phone"] = $this->request->getPost('phone');
            $data["email"] = $this->request->getPost('email');
            $data["organization"] = $this->request->getPost('organization');
        }

        // Handle File Attachments
        $target_path = get_setting("timeline_file_path");
        $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "visitor_appointment");
        $new_files = unserialize($files_data);

        if ($id) {
            $appointment_info = $this->Visitor_appointments_model->get_one($id);
            $new_files = update_saved_files($target_path, $appointment_info->files, $new_files);
        }

        $data["files"] = serialize($new_files);

        $save_id = $this->Visitor_appointments_model->ci_save($data, $id);

        if ($save_id) {
            // Send Internal Message Notification to host officer
            if (!$id) {
                $visitor_display_name = $data["appointment_type"] === "internal"
                    ? $this->login_user->first_name . " " . $this->login_user->last_name . " (Internal Staff)"
                    : $data["first_name"] . " " . $data["last_name"] . ($data["organization"] ? " (" . $data["organization"] . ")" : "");

                $msg_body = "Hello,\n\nAn appointment has been scheduled with you.\n\nVisitor / Requester: " . $visitor_display_name . "\nDate: " . $appointment_date . "\nTime: " . $appointment_time . "\nReason: " . $reason . "\n\nPlease review and respond to this appointment in the system.";

                $message_data = array(
                    "to_user_id" => $to_user_id,
                    "from_user_id" => $this->login_user->id,
                    "subject" => "New Visitor Appointment: " . $visitor_display_name,
                    "message" => $msg_body,
                    "files" => "",
                    "deleted_by_users" => "",
                    "created_at" => get_current_utc_time()
                );

                $this->Messages_model->ci_save($message_data);
            }

            echo json_encode(array("success" => true, "id" => $save_id, "data" => $this->_make_row($this->Visitor_appointments_model->get_details(array("id" => $save_id))->getRow()), "message" => app_lang('record_saved')));
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
        $appointment_info = $this->Visitor_appointments_model->get_details($options)->getRow();

        if (!$appointment_info) {
            show_404();
        }

        $view_data['model_info'] = $appointment_info;
        return $this->template->view('visitor_appointment/view_modal', $view_data);
    }

    // Status Update Modal (Approve, Reject, Reschedule)
    function status_modal($id = 0) {
        if (!$id) {
            show_404();
        }

        $options = array("id" => $id);
        $appointment_info = $this->Visitor_appointments_model->get_details($options)->getRow();

        if (!$appointment_info) {
            show_404();
        }

        $view_data['model_info'] = $appointment_info;
        return $this->template->view('visitor_appointment/status_modal', $view_data);
    }

    // Save Status Update
    function save_status() {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $status_reason = $this->request->getPost('status_reason');
        $rescheduled_date = $this->request->getPost('rescheduled_date');
        $rescheduled_time = $this->request->getPost('rescheduled_time');

        $this->validate_submitted_data(array(
            "id" => "required|numeric",
            "status" => "required",
            "status_reason" => "required"
        ));

        $data = array(
            "status" => $status,
            "status_by" => $this->login_user->id,
            "status_reason" => $status_reason,
            "updated_at" => get_current_utc_time()
        );

        if ($status === "rescheduled") {
            if ($rescheduled_date) {
                $data["rescheduled_date"] = $rescheduled_date;
                $data["appointment_date"] = $rescheduled_date;
            }
            if ($rescheduled_time) {
                $data["rescheduled_time"] = $rescheduled_time;
                $data["appointment_time"] = $rescheduled_time;
            }
        }

        $save_id = $this->Visitor_appointments_model->ci_save($data, $id);

        if ($save_id) {
            // Notify Creator via internal message
            $appointment_info = $this->Visitor_appointments_model->get_details(array("id" => $id))->getRow();
            
            $msg_body = "Hello,\n\nThe status of your appointment (ID: #" . $id . ") has been updated to: " . strtoupper($status) . ".\n\nUpdated By: " . $this->login_user->first_name . " " . $this->login_user->last_name . "\nReason / Remarks: " . $status_reason;
            if ($status === "rescheduled") {
                $msg_body .= "\nNew Date: " . $appointment_info->appointment_date . "\nNew Time: " . $appointment_info->appointment_time;
            }

            $message_data = array(
                "to_user_id" => $appointment_info->created_by,
                "from_user_id" => $this->login_user->id,
                "subject" => "Appointment Update #" . $id . ": " . strtoupper($status),
                "message" => $msg_body,
                "files" => "",
                "deleted_by_users" => "",
                "created_at" => get_current_utc_time()
            );

            $this->Messages_model->ci_save($message_data);

            echo json_encode(array("success" => true, "id" => $save_id, "data" => $this->_make_row($appointment_info), "message" => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    // Delete Record
    function delete() {
        $id = $this->request->getPost('id');
        if ($this->request->getPost('undo')) {
            if ($this->Visitor_appointments_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_make_row($this->Visitor_appointments_model->get_details(array("id" => $id))->getRow()), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
            }
        } else {
            if ($this->Visitor_appointments_model->delete($id)) {
                echo json_encode(array("success" => true, 'id' => $id, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
            }
        }
    }

    // Download/Print PDF Slip
    function download_pdf($id = 0) {
        if (!$id) {
            show_404();
        }

        $appointment_info = $this->Visitor_appointments_model->get_details(array("id" => $id))->getRow();

        if (!$appointment_info) {
            show_404();
        }

        $view_data['model_info'] = $appointment_info;
        $html = view("visitor_appointment/pdf", $view_data);

        $pdf = new Pdf();
        $pdf->PreparePDF($html, "Visitor_Appointment_" . $id . ".pdf", "download");
    }
}
