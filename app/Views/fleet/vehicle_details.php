<div id="page-content" class="page-wrapper clearfix">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4><i data-feather="truck" class="icon-16 mr5"></i> <?php echo $vehicle->plate_number; ?></h4>
                    <div class="title-button-group">
                        <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "fleet") !== "read_only") { ?>
                            <?php echo js_anchor("<i data-feather='edit-2' class='icon-16'></i>", array("class" => "btn btn-sm btn-outline-info", "data-act" => "ajax-modal", "data-action-url" => get_uri("fleet/vehicle_modal_form/" . $vehicle->id), "data-title" => app_lang("edit_vehicle"))); ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    $status_map = array(
                        "available"      => "success",
                        "in_field"       => "primary",
                        "maintenance"    => "warning",
                        "decommissioned" => "secondary",
                    );
                    $badge_class = get_array_value($status_map, $vehicle->status) ?: "light";
                    ?>
                    <div class="mb15">
                        <span class="badge bg-<?php echo $badge_class; ?> fs-6">
                            <?php echo app_lang("fleet_" . $vehicle->status) ?: $vehicle->status; ?>
                        </span>
                    </div>

                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="strong"><?php echo app_lang("fleet_make"); ?></td>
                            <td><?php echo $vehicle->make; ?></td>
                        </tr>
                        <tr>
                            <td class="strong"><?php echo app_lang("fleet_model"); ?></td>
                            <td><?php echo $vehicle->model; ?></td>
                        </tr>
                        <tr>
                            <td class="strong"><?php echo app_lang("fleet_year"); ?></td>
                            <td><?php echo $vehicle->year; ?></td>
                        </tr>
                        <tr>
                            <td class="strong"><?php echo app_lang("fleet_color"); ?></td>
                            <td><?php echo $vehicle->color ?: "-"; ?></td>
                        </tr>
                        <tr>
                            <td class="strong"><?php echo app_lang("vin"); ?></td>
                            <td><code><?php echo $vehicle->vin ?: "-"; ?></code></td>
                        </tr>
                        <tr>
                            <td class="strong"><?php echo app_lang("fleet_fuel_type"); ?></td>
                            <td><?php echo $vehicle->fuel_type ? app_lang("fleet_" . $vehicle->fuel_type) : "-"; ?></td>
                        </tr>
                        <tr>
                            <td class="strong"><?php echo app_lang("fleet_mileage"); ?></td>
                            <td><?php echo $vehicle->mileage ? number_format($vehicle->mileage) . " km" : "-"; ?></td>
                        </tr>
                        <tr>
                            <td class="strong"><?php echo app_lang("fleet_last_service"); ?></td>
                            <td><?php echo $vehicle->last_service_date ? format_to_date($vehicle->last_service_date, false) : "-"; ?></td>
                        </tr>
                        <tr>
                            <td class="strong"><?php echo app_lang("fleet_next_service"); ?></td>
                            <td>
                                <?php if ($vehicle->next_service_date): ?>
                                    <?php
                                    $days_left = (strtotime($vehicle->next_service_date) - time()) / 86400;
                                    $cls = $days_left <= 0 ? "text-danger" : ($days_left <= 30 ? "text-warning" : "");
                                    ?>
                                    <span class="<?php echo $cls; ?>"><?php echo format_to_date($vehicle->next_service_date, false); ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>

                    <?php if ($vehicle->notes): ?>
                    <hr/>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($vehicle->notes)); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($service_summary): ?>
            <div class="card mt15">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-6">
                            <h3 class="mb0 text-primary"><?php echo $service_summary->total_services; ?></h3>
                            <small class="text-muted"><?php echo app_lang("fleet_total_services"); ?></small>
                        </div>
                        <div class="col-6">
                            <h3 class="mb0 text-success"><?php echo format_currency($service_summary->total_cost); ?></h3>
                            <small class="text-muted"><?php echo app_lang("fleet_total_service_cost"); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <!-- Service Logs -->
            <div class="card mb15">
                <div class="card-header">
                    <h4><i data-feather="tool" class="icon-14 mr5"></i> <?php echo app_lang("fleet_service_logs"); ?></h4>
                    <div class="title-button-group">
                        <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "fleet") !== "read_only") { ?>
                            <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("add_service_log"), array("class" => "btn btn-sm btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("fleet/service_log_modal_form"), "data-title" => app_lang("add_service_log"))); ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body p0">
                    <table id="vehicle-service-table" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><?php echo app_lang("fleet_service_type"); ?></th>
                                <th><?php echo app_lang("fleet_service_date"); ?></th>
                                <th><?php echo app_lang("fleet_mileage_at_service"); ?></th>
                                <th><?php echo app_lang("fleet_service_cost"); ?></th>
                                <th><?php echo app_lang("fleet_service_provider"); ?></th>
                                <th class="text-center w100"><?php echo app_lang("action"); ?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Routes -->
            <div class="card">
                <div class="card-header">
                    <h4><i data-feather="map-pin" class="icon-14 mr5"></i> <?php echo app_lang("fleet_routes"); ?></h4>
                    <div class="title-button-group">
                        <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "fleet") !== "read_only") { ?>
                            <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("add_route"), array("class" => "btn btn-sm btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("fleet/route_modal_form"), "data-title" => app_lang("add_route"))); ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-body p0">
                    <table id="vehicle-routes-table" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><?php echo app_lang("title"); ?></th>
                                <th><?php echo app_lang("fleet_route"); ?></th>
                                <th><?php echo app_lang("fleet_scheduled_date"); ?></th>
                                <th><?php echo app_lang("status"); ?></th>
                                <th class="text-center w100"><?php echo app_lang("action"); ?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    var vehicleId = <?php echo (int)$vehicle_id; ?>;

    // service row: [vehicle_label, type, date, mileage, cost, provider, next_service_date, actions]
    $("#vehicle-service-table").appTable({
        source: "<?php echo_uri('fleet/service_logs_list_data'); ?>",
        order: [[2, 'desc']],
        requestData: {vehicle_id: vehicleId},
        columns: [
            {title: "<?php echo app_lang('vehicle'); ?>"},
            {title: "<?php echo app_lang('fleet_service_type'); ?>"},
            {title: "<?php echo app_lang('fleet_service_date'); ?>"},
            {title: "<?php echo app_lang('fleet_mileage_at_service'); ?>"},
            {title: "<?php echo app_lang('fleet_service_cost'); ?>"},
            {title: "<?php echo app_lang('fleet_service_provider'); ?>"},
            {title: "<?php echo app_lang('fleet_next_service'); ?>"},
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
        ],
        columnDefs: [{visible: false, targets: [0, 6]}]
    });

    // route row: [title, vehicle_label, route, driver, scheduled_date, status, actions]
    $("#vehicle-routes-table").appTable({
        source: "<?php echo_uri('fleet/routes_list_data'); ?>",
        order: [[4, 'desc']],
        requestData: {vehicle_id: vehicleId},
        columns: [
            {title: "<?php echo app_lang('title'); ?>"},
            {title: "<?php echo app_lang('vehicle'); ?>"},
            {title: "<?php echo app_lang('fleet_route'); ?>"},
            {title: "<?php echo app_lang('fleet_driver'); ?>"},
            {title: "<?php echo app_lang('fleet_scheduled_date'); ?>"},
            {title: "<?php echo app_lang('status'); ?>"},
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
        ],
        columnDefs: [{visible: false, targets: [1, 3]}]
    });
});
</script>
