<?php

namespace App\Controllers;

class Fleet extends Security_Controller {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("fleet");
    }

    private function _check_fleet_access() {
        if (!get_setting("module_fleet")) {
            app_redirect("forbidden");
        }
        $this->access_only_team_members();
        if (!($this->login_user->is_admin || get_array_value($this->login_user->permissions, "fleet"))) {
            app_redirect("forbidden");
        }
    }

    private function _require_manage() {
        $this->_check_fleet_access();
        $perm = get_array_value($this->login_user->permissions, "fleet");
        if (!$this->login_user->is_admin && $perm === "read_only") {
            app_redirect("forbidden");
        }
    }

    /* ─── VEHICLES ─────────────────────────────────────────────────── */

    function index() {
        $this->_check_fleet_access();

        $view_data['status_counts'] = $this->Fleet_vehicles_model->count_by_status();
        $view_data['service_due']   = $this->Fleet_vehicles_model->get_service_due(30);
        $view_data['page_type']     = 'vehicles';

        $status_options = array(
            ""               => "-- " . app_lang("all") . " --",
            "available"      => app_lang("fleet_available"),
            "in_field"       => app_lang("fleet_in_field"),
            "maintenance"    => app_lang("fleet_maintenance"),
            "decommissioned" => app_lang("fleet_decommissioned"),
        );
        $view_data['status_filter_dropdown'] = $status_options;

        return $this->template->rander("fleet/index", $view_data);
    }

    function list_data() {
        $this->_check_fleet_access();

        $search = $this->request->getPost("search");
        $status = $this->request->getPost("status");

        $options = array("search" => $search, "status" => $status);
        $result  = $this->Fleet_vehicles_model->get_details($options)->getResult();

        $list_data = array();
        foreach ($result as $row) {
            $list_data[] = $this->_make_vehicle_row($row);
        }

        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(array("data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found));
    }

    private function _make_vehicle_row($row) {
        $status_badges = array(
            "available"      => "<span class='badge bg-success'>" . app_lang("fleet_available") . "</span>",
            "in_field"       => "<span class='badge bg-primary'>" . app_lang("fleet_in_field") . "</span>",
            "maintenance"    => "<span class='badge bg-warning text-dark'>" . app_lang("fleet_maintenance") . "</span>",
            "decommissioned" => "<span class='badge bg-secondary'>" . app_lang("fleet_decommissioned") . "</span>",
        );
        $status_badge = get_array_value($status_badges, $row->status) ?: "<span class='badge bg-light text-dark'>{$row->status}</span>";

        $service_alert = "";
        if ($row->next_service_date && strtotime($row->next_service_date) <= strtotime("+30 days")) {
            $service_alert = "<i data-feather='alert-triangle' class='icon-14 text-warning ml5' title='" . app_lang("fleet_service_due_soon") . "'></i>";
        }

        $can_manage = $this->login_user->is_admin || get_array_value($this->login_user->permissions, "fleet") !== "read_only";

        $actions = anchor(get_uri("fleet/view/" . $row->id), "<i data-feather='eye' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-primary mr5", "title" => app_lang("view")));
        if ($can_manage) {
            $actions .= js_anchor("<i data-feather='edit-2' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-info mr5", "title" => app_lang("edit"), "data-act" => "ajax-modal", "data-action-url" => get_uri("fleet/vehicle_modal_form/" . $row->id), "data-title" => app_lang("edit_vehicle")));
            $actions .= js_anchor("<i data-feather='trash-2' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-danger", "title" => app_lang("delete"), "data-action-url" => get_uri("fleet/delete_vehicle/" . $row->id), "data-action" => "delete-confirmation"));
        }

        return array(
            $row->plate_number . " " . $service_alert,
            $row->vin,
            $row->make . " " . $row->model . " (" . $row->year . ")",
            $row->fuel_type,
            $status_badge,
            $row->driver_name ?: "-",
            $row->mileage ? number_format($row->mileage) . " km" : "-",
            $row->next_service_date ? format_to_date($row->next_service_date, false) : "-",
            $actions,
        );
    }

    function vehicle_modal_form($id = 0) {
        $this->_check_fleet_access();

        $view_data['model_info'] = $this->Fleet_vehicles_model->get_one($id);

        $users = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->getResult();
        $driver_dropdown = array("" => "-- " . app_lang("select") . " --");
        foreach ($users as $u) {
            $driver_dropdown[$u->id] = $u->first_name . " " . $u->last_name;
        }
        $view_data['driver_dropdown'] = $driver_dropdown;

        $view_data['fuel_type_dropdown'] = array(
            "petrol"   => app_lang("fleet_petrol"),
            "diesel"   => app_lang("fleet_diesel"),
            "electric" => app_lang("fleet_electric"),
            "hybrid"   => app_lang("fleet_hybrid"),
            "other"    => app_lang("other"),
        );
        $view_data['status_dropdown'] = array(
            "available"      => app_lang("fleet_available"),
            "in_field"       => app_lang("fleet_in_field"),
            "maintenance"    => app_lang("fleet_maintenance"),
            "decommissioned" => app_lang("fleet_decommissioned"),
        );

        return $this->template->view("fleet/vehicle_modal_form", $view_data);
    }

    function save_vehicle() {
        $this->_require_manage();

        $this->validate_submitted_data(array(
            "plate_number" => "required",
            "make"         => "required",
            "model"        => "required",
            "year"         => "required|numeric",
        ));

        $id = $this->request->getPost("id");
        $data = array(
            "vin"              => $this->request->getPost("vin"),
            "plate_number"     => $this->request->getPost("plate_number"),
            "make"             => $this->request->getPost("make"),
            "model"            => $this->request->getPost("model"),
            "year"             => (int)$this->request->getPost("year"),
            "color"            => $this->request->getPost("color"),
            "fuel_type"        => $this->request->getPost("fuel_type"),
            "status"           => $this->request->getPost("status"),
            "assigned_driver"  => (int)$this->request->getPost("assigned_driver"),
            "mileage"          => (int)$this->request->getPost("mileage"),
            "notes"            => $this->request->getPost("notes"),
            "updated_at"       => get_current_utc_time(),
        );

        $next = $this->request->getPost("next_service_date");
        $last = $this->request->getPost("last_service_date");
        $data["next_service_date"] = $next ? $next : null;
        $data["last_service_date"] = $last ? $last : null;

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Fleet_vehicles_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "id" => $save_id, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    function delete_vehicle($id = 0) {
        $this->_require_manage();
        if ($this->Fleet_vehicles_model->delete_one($id)) {
            echo json_encode(array("success" => true, "message" => app_lang("record_deleted")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    function view($id = 0) {
        $this->_check_fleet_access();

        $vehicle = $this->Fleet_vehicles_model->get_one($id);
        if (!$vehicle || !$vehicle->id) {
            app_redirect("forbidden");
        }

        $view_data['vehicle']   = $vehicle;
        $view_data['vehicle_id']= $id;

        $routes = $this->Fleet_routes_model->get_details(array("vehicle_id" => $id))->getResult();
        $view_data['routes'] = $routes;

        $service_logs = $this->Fleet_service_logs_model->get_details(array("vehicle_id" => $id))->getResult();
        $view_data['service_logs'] = $service_logs;

        $service_summary = $this->Fleet_service_logs_model->get_service_summary($id);
        $view_data['service_summary'] = $service_summary;

        return $this->template->rander("fleet/vehicle_details", $view_data);
    }

    /* ─── ROUTES ────────────────────────────────────────────────────── */

    function routes() {
        $this->_check_fleet_access();

        $vehicles = $this->Fleet_vehicles_model->get_vehicle_dropdown();
        $vehicle_dropdown = array("" => "-- " . app_lang("all_vehicles") . " --");
        foreach ($vehicles as $v) {
            $vehicle_dropdown[$v->id] = $v->title;
        }
        $view_data['vehicle_dropdown'] = $vehicle_dropdown;

        $view_data['status_filter_dropdown'] = array(
            ""          => "-- " . app_lang("all") . " --",
            "planned"   => app_lang("fleet_route_planned"),
            "active"    => app_lang("fleet_route_active"),
            "completed" => app_lang("fleet_route_completed"),
            "cancelled" => app_lang("fleet_route_cancelled"),
        );

        $view_data['page_type'] = 'routes';
        return $this->template->rander("fleet/routes", $view_data);
    }

    function routes_list_data() {
        $this->_check_fleet_access();

        $options = array(
            "vehicle_id" => $this->request->getPost("vehicle_id"),
            "status"     => $this->request->getPost("status"),
            "search"     => $this->request->getPost("search"),
        );
        $result = $this->Fleet_routes_model->get_details($options)->getResult();

        $list_data = array();
        foreach ($result as $row) {
            $list_data[] = $this->_make_route_row($row);
        }

        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(array("data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found));
    }

    private function _make_route_row($row) {
        $status_badges = array(
            "planned"   => "<span class='badge bg-secondary'>" . app_lang("fleet_route_planned") . "</span>",
            "active"    => "<span class='badge bg-primary'>" . app_lang("fleet_route_active") . "</span>",
            "completed" => "<span class='badge bg-success'>" . app_lang("fleet_route_completed") . "</span>",
            "cancelled" => "<span class='badge bg-danger'>" . app_lang("fleet_route_cancelled") . "</span>",
        );
        $status_badge = get_array_value($status_badges, $row->status) ?: "<span class='badge bg-light'>{$row->status}</span>";
        $can_manage = $this->login_user->is_admin || get_array_value($this->login_user->permissions, "fleet") !== "read_only";

        $actions = "";
        if ($can_manage) {
            $actions .= js_anchor("<i data-feather='edit-2' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-info mr5", "title" => app_lang("edit"), "data-act" => "ajax-modal", "data-action-url" => get_uri("fleet/route_modal_form/" . $row->id), "data-title" => app_lang("edit_route")));
            $actions .= js_anchor("<i data-feather='trash-2' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-danger", "title" => app_lang("delete"), "data-action-url" => get_uri("fleet/delete_route/" . $row->id), "data-action" => "delete-confirmation"));
        }

        return array(
            $row->title,
            $row->vehicle_label ?: "-",
            $row->start_location . " &rarr; " . $row->end_location,
            $row->driver_name ?: "-",
            $row->scheduled_date ? format_to_date($row->scheduled_date, false) : "-",
            $status_badge,
            $actions,
        );
    }

    function route_modal_form($id = 0) {
        $this->_check_fleet_access();

        $view_data['model_info'] = $this->Fleet_routes_model->get_one($id);

        $vehicles = $this->Fleet_vehicles_model->get_vehicle_dropdown();
        $vehicle_dropdown = array("" => "-- " . app_lang("select_vehicle") . " --");
        foreach ($vehicles as $v) {
            $vehicle_dropdown[$v->id] = $v->title;
        }
        $view_data['vehicle_dropdown'] = $vehicle_dropdown;

        $users = $this->Users_model->get_all_where(array("deleted" => 0, "user_type" => "staff"))->getResult();
        $driver_dropdown = array("" => "-- " . app_lang("select") . " --");
        foreach ($users as $u) {
            $driver_dropdown[$u->id] = $u->first_name . " " . $u->last_name;
        }
        $view_data['driver_dropdown'] = $driver_dropdown;

        $view_data['status_dropdown'] = array(
            "planned"   => app_lang("fleet_route_planned"),
            "active"    => app_lang("fleet_route_active"),
            "completed" => app_lang("fleet_route_completed"),
            "cancelled" => app_lang("fleet_route_cancelled"),
        );

        return $this->template->view("fleet/route_modal_form", $view_data);
    }

    function save_route() {
        $this->_require_manage();

        $this->validate_submitted_data(array(
            "title"          => "required",
            "start_location" => "required",
            "end_location"   => "required",
        ));

        $id = $this->request->getPost("id");
        $data = array(
            "title"              => $this->request->getPost("title"),
            "vehicle_id"         => (int)$this->request->getPost("vehicle_id"),
            "assigned_driver"    => (int)$this->request->getPost("assigned_driver"),
            "start_location"     => $this->request->getPost("start_location"),
            "end_location"       => $this->request->getPost("end_location"),
            "waypoints"          => $this->request->getPost("waypoints"),
            "distance_km"        => (float)$this->request->getPost("distance_km"),
            "estimated_duration" => $this->request->getPost("estimated_duration"),
            "status"             => $this->request->getPost("status"),
            "notes"              => $this->request->getPost("notes"),
        );

        $sched = $this->request->getPost("scheduled_date");
        $data["scheduled_date"] = $sched ? $sched : null;

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Fleet_routes_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "id" => $save_id, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    function delete_route($id = 0) {
        $this->_require_manage();
        if ($this->Fleet_routes_model->delete_one($id)) {
            echo json_encode(array("success" => true, "message" => app_lang("record_deleted")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── SERVICE LOGS ──────────────────────────────────────────────── */

    function service_logs() {
        $this->_check_fleet_access();
        $view_data['page_type'] = 'service_logs';
        return $this->template->rander("fleet/service_logs", $view_data);
    }

    function service_logs_list_data() {
        $this->_check_fleet_access();
        $options = array(
            "vehicle_id" => $this->request->getPost("vehicle_id"),
            "search"     => $this->request->getPost("search"),
        );
        $result = $this->Fleet_service_logs_model->get_details($options)->getResult();

        $list_data = array();
        foreach ($result as $row) {
            $list_data[] = $this->_make_service_row($row);
        }
        $found = !empty($result) ? $result[0]->_pg_found_rows : 0;
        echo json_encode(array("data" => $list_data, "recordsTotal" => $found, "recordsFiltered" => $found));
    }

    private function _make_service_row($row) {
        $can_manage = $this->login_user->is_admin || get_array_value($this->login_user->permissions, "fleet") !== "read_only";
        $actions = "";
        if ($can_manage) {
            $actions .= js_anchor("<i data-feather='edit-2' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-info mr5", "title" => app_lang("edit"), "data-act" => "ajax-modal", "data-action-url" => get_uri("fleet/service_log_modal_form/" . $row->id), "data-title" => app_lang("edit_service_log")));
            $actions .= js_anchor("<i data-feather='trash-2' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-danger", "title" => app_lang("delete"), "data-action-url" => get_uri("fleet/delete_service_log/" . $row->id), "data-action" => "delete-confirmation"));
        }

        $service_type_labels = array(
            "oil_change"    => app_lang("fleet_service_oil_change"),
            "tire_rotation" => app_lang("fleet_service_tire_rotation"),
            "inspection"    => app_lang("fleet_service_inspection"),
            "repair"        => app_lang("fleet_service_repair"),
            "other"         => app_lang("other"),
        );
        $type_label = get_array_value($service_type_labels, $row->service_type) ?: $row->service_type;

        return array(
            $row->vehicle_label ?: ($row->plate_number ?: "-"),
            $type_label,
            format_to_date($row->service_date, false),
            number_format($row->mileage_at_service) . " km",
            format_currency($row->cost),
            $row->service_provider ?: "-",
            $row->next_service_date ? format_to_date($row->next_service_date, false) : "-",
            $actions,
        );
    }

    function service_log_modal_form($id = 0) {
        $this->_check_fleet_access();

        $view_data['model_info'] = $this->Fleet_service_logs_model->get_one($id);

        $vehicles = $this->Fleet_vehicles_model->get_vehicle_dropdown();
        $vehicle_dropdown = array("" => "-- " . app_lang("select_vehicle") . " --");
        foreach ($vehicles as $v) {
            $vehicle_dropdown[$v->id] = $v->title;
        }
        $view_data['vehicle_dropdown'] = $vehicle_dropdown;

        $view_data['service_type_dropdown'] = array(
            "oil_change"    => app_lang("fleet_service_oil_change"),
            "tire_rotation" => app_lang("fleet_service_tire_rotation"),
            "inspection"    => app_lang("fleet_service_inspection"),
            "repair"        => app_lang("fleet_service_repair"),
            "other"         => app_lang("other"),
        );

        return $this->template->view("fleet/service_log_modal_form", $view_data);
    }

    function save_service_log() {
        $this->_require_manage();

        $this->validate_submitted_data(array(
            "vehicle_id"   => "required|numeric",
            "service_type" => "required",
            "service_date" => "required",
        ));

        $id = $this->request->getPost("id");
        $data = array(
            "vehicle_id"          => (int)$this->request->getPost("vehicle_id"),
            "service_type"        => $this->request->getPost("service_type"),
            "service_date"        => $this->request->getPost("service_date"),
            "mileage_at_service"  => (int)$this->request->getPost("mileage_at_service"),
            "cost"                => (float)$this->request->getPost("cost"),
            "service_provider"    => $this->request->getPost("service_provider"),
            "next_service_mileage"=> (int)$this->request->getPost("next_service_mileage"),
            "description"         => $this->request->getPost("description"),
        );

        $next = $this->request->getPost("next_service_date");
        $data["next_service_date"] = $next ? $next : null;

        if (!$id) {
            $data["created_by"] = $this->login_user->id;
            $data["created_at"] = get_current_utc_time();
        }

        $save_id = $this->Fleet_service_logs_model->ci_save($data, $id);

        if ($save_id) {
            // Update vehicle's last/next service date
            $vehicle_update = array(
                "last_service_date" => $data["service_date"],
                "updated_at"        => get_current_utc_time(),
            );
            if ($next) {
                $vehicle_update["next_service_date"] = $next;
            }
            $this->Fleet_vehicles_model->ci_save($vehicle_update, $data["vehicle_id"]);

            echo json_encode(array("success" => true, "id" => $save_id, "message" => app_lang("record_saved")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    function delete_service_log($id = 0) {
        $this->_require_manage();
        if ($this->Fleet_service_logs_model->delete_one($id)) {
            echo json_encode(array("success" => true, "message" => app_lang("record_deleted")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("error_occurred")));
        }
    }

    /* ─── REPORT ────────────────────────────────────────────────────── */

    function fleet_report() {
        $this->_check_fleet_access();

        $status_counts = $this->Fleet_vehicles_model->count_by_status();
        $status_data   = array();
        $total_vehicles = 0;
        foreach ($status_counts as $s) {
            $status_data[$s->status] = (int)$s->total;
            $total_vehicles += (int)$s->total;
        }

        $view_data['status_data']    = json_encode($status_data);
        $view_data['total_vehicles'] = $total_vehicles;
        $view_data['service_due']    = $this->Fleet_vehicles_model->get_service_due(30);
        $view_data['recent_service'] = $this->Fleet_service_logs_model->get_details(array())->getResult();

        return $this->template->rander("fleet/report", $view_data);
    }
}
