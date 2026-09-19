<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="truck" class="icon-16 mr5"></i> <?php echo app_lang("fleet_management"); ?></h4>
            <div class="title-button-group">
                <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "fleet") !== "read_only") { ?>
                    <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("add_vehicle"), array("class" => "btn btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("fleet/vehicle_modal_form"), "data-title" => app_lang("add_vehicle"))); ?>
                <?php } ?>
            </div>
        </div>

        <!-- Status summary cards -->
        <div class="row p15 pb0">
            <?php
            $status_map = array(
                "available"      => array("label" => app_lang("fleet_available"),      "color" => "success",   "icon" => "check-circle"),
                "in_field"       => array("label" => app_lang("fleet_in_field"),       "color" => "primary",   "icon" => "map-pin"),
                "maintenance"    => array("label" => app_lang("fleet_maintenance"),    "color" => "warning",   "icon" => "tool"),
                "decommissioned" => array("label" => app_lang("fleet_decommissioned"), "color" => "secondary", "icon" => "x-circle"),
            );
            $counts = array();
            foreach ($status_counts as $s) { $counts[$s->status] = $s->total; }
            foreach ($status_map as $key => $info) {
                $count = get_array_value($counts, $key) ?: 0;
            ?>
            <div class="col-md-3 col-sm-6 mb15">
                <div class="card border-<?php echo $info['color']; ?> h-100">
                    <div class="card-body text-center">
                        <i data-feather="<?php echo $info['icon']; ?>" class="icon-32 text-<?php echo $info['color']; ?>"></i>
                        <h3 class="mt5 mb0 text-<?php echo $info['color']; ?>"><?php echo $count; ?></h3>
                        <small><?php echo $info['label']; ?></small>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <?php if (count($service_due)) { ?>
        <div class="p15 pt0">
            <div class="alert alert-warning mb0">
                <i data-feather="alert-triangle" class="icon-16 mr5"></i>
                <strong><?php echo count($service_due); ?></strong> <?php echo app_lang("fleet_vehicles_service_due"); ?>:
                <?php foreach ($service_due as $v) { ?>
                    <?php echo anchor(get_uri("fleet/view/" . $v->id), "<strong>{$v->plate_number}</strong> ({$v->make} {$v->model})", array("class" => "mr10")); ?>
                <?php } ?>
            </div>
        </div>
        <?php } ?>

        <!-- Filters -->
        <div class="card-body pt0">
            <div class="row mb15">
                <div class="col-md-4">
                    <?php echo form_dropdown("status_filter", $status_filter_dropdown, "", "id='fleet-status-filter' class='select2'"); ?>
                </div>
                <div class="col-md-4">
                    <input type="text" id="fleet-search" class="form-control" placeholder="<?php echo app_lang('search_vehicles'); ?>" />
                </div>
            </div>

            <table id="fleet-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo app_lang("plate_number"); ?></th>
                        <th><?php echo app_lang("vin"); ?></th>
                        <th><?php echo app_lang("vehicle"); ?></th>
                        <th><?php echo app_lang("fleet_fuel_type"); ?></th>
                        <th><?php echo app_lang("status"); ?></th>
                        <th><?php echo app_lang("fleet_driver"); ?></th>
                        <th><?php echo app_lang("fleet_mileage"); ?></th>
                        <th><?php echo app_lang("fleet_next_service"); ?></th>
                        <th class="text-center w100"><?php echo app_lang("action"); ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    var loadTable = function () {
        $("#fleet-table").appTable({
            source: "<?php echo_uri('fleet/list_data'); ?>",
            order: [[0, 'asc']],
            requestData: function () {
                return {
                    status: $("#fleet-status-filter").val(),
                    search: $("#fleet-search").val()
                };
            },
            columns: [
                {title: "<?php echo app_lang('plate_number'); ?>"},
                {title: "<?php echo app_lang('vin'); ?>"},
                {title: "<?php echo app_lang('vehicle'); ?>"},
                {title: "<?php echo app_lang('fleet_fuel_type'); ?>"},
                {title: "<?php echo app_lang('status'); ?>"},
                {title: "<?php echo app_lang('fleet_driver'); ?>"},
                {title: "<?php echo app_lang('fleet_mileage'); ?>"},
                {title: "<?php echo app_lang('fleet_next_service'); ?>"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    };
    loadTable();

    $("#fleet-status-filter, #fleet-search").on("change keyup", function () {
        $("#fleet-table").appTable({reload: true});
    });
});
</script>
