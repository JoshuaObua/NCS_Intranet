<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="map-pin" class="icon-16 mr5"></i> <?php echo app_lang("fleet_routes"); ?></h4>
            <div class="title-button-group">
                <?php echo anchor(get_uri("fleet"), "<i data-feather='truck' class='icon-16'></i> " . app_lang("fleet_vehicles"), array("class" => "btn btn-default mr5")); ?>
                <?php echo anchor(get_uri("fleet/service_logs"), "<i data-feather='tool' class='icon-16'></i> " . app_lang("fleet_service_logs"), array("class" => "btn btn-default mr5")); ?>
                <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "fleet") !== "read_only") { ?>
                    <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("add_route"), array("class" => "btn btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("fleet/route_modal_form"), "data-title" => app_lang("add_route"))); ?>
                <?php } ?>
            </div>
        </div>

        <div class="card-body pt0">
            <div class="row mb15">
                <div class="col-md-3">
                    <?php echo form_dropdown("vehicle_filter", $vehicle_dropdown, "", "id='fleet-vehicle-filter' class='select2'"); ?>
                </div>
                <div class="col-md-3">
                    <?php echo form_dropdown("status_filter", $status_filter_dropdown, "", "id='fleet-status-filter' class='select2'"); ?>
                </div>
                <div class="col-md-4">
                    <input type="text" id="fleet-search" class="form-control" placeholder="<?php echo app_lang('search'); ?>" />
                </div>
            </div>

            <table id="routes-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo app_lang("title"); ?></th>
                        <th><?php echo app_lang("vehicle"); ?></th>
                        <th><?php echo app_lang("fleet_route"); ?></th>
                        <th><?php echo app_lang("fleet_driver"); ?></th>
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

<script>
$(document).ready(function () {
    $("#routes-table").appTable({
        source: "<?php echo_uri('fleet/routes_list_data'); ?>",
        order: [[4, 'desc']],
        requestData: function () {
            return {
                vehicle_id: $("#fleet-vehicle-filter").val(),
                status: $("#fleet-status-filter").val(),
                search: $("#fleet-search").val()
            };
        },
        columns: [
            {title: "<?php echo app_lang('title'); ?>"},
            {title: "<?php echo app_lang('vehicle'); ?>"},
            {title: "<?php echo app_lang('fleet_route'); ?>"},
            {title: "<?php echo app_lang('fleet_driver'); ?>"},
            {title: "<?php echo app_lang('fleet_scheduled_date'); ?>"},
            {title: "<?php echo app_lang('status'); ?>"},
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
        ]
    });

    $("#fleet-vehicle-filter, #fleet-status-filter, #fleet-search").on("change keyup", function () {
        $("#routes-table").appTable({reload: true});
    });
});
</script>
