<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="tool" class="icon-16 mr5"></i> <?php echo app_lang("fleet_service_logs"); ?></h4>
            <div class="title-button-group">
                <?php echo anchor(get_uri("fleet"), "<i data-feather='truck' class='icon-16'></i> " . app_lang("fleet_vehicles"), array("class" => "btn btn-default mr5")); ?>
                <?php echo anchor(get_uri("fleet/routes"), "<i data-feather='map-pin' class='icon-16'></i> " . app_lang("fleet_routes"), array("class" => "btn btn-default mr5")); ?>
                <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "fleet") !== "read_only") { ?>
                    <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("add_service_log"), array("class" => "btn btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("fleet/service_log_modal_form"), "data-title" => app_lang("add_service_log"))); ?>
                <?php } ?>
            </div>
        </div>

        <div class="card-body pt0">
            <div class="row mb15">
                <div class="col-md-4">
                    <input type="text" id="fleet-search" class="form-control" placeholder="<?php echo app_lang('search'); ?>" />
                </div>
            </div>

            <table id="service-logs-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo app_lang("vehicle"); ?></th>
                        <th><?php echo app_lang("fleet_service_type"); ?></th>
                        <th><?php echo app_lang("fleet_service_date"); ?></th>
                        <th><?php echo app_lang("fleet_mileage_at_service"); ?></th>
                        <th><?php echo app_lang("fleet_service_cost"); ?></th>
                        <th><?php echo app_lang("fleet_service_provider"); ?></th>
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
    $("#service-logs-table").appTable({
        source: "<?php echo_uri('fleet/service_logs_list_data'); ?>",
        order: [[2, 'desc']],
        requestData: function () {
            return {
                search: $("#fleet-search").val()
            };
        },
        columns: [
            {title: "<?php echo app_lang('vehicle'); ?>"},
            {title: "<?php echo app_lang('fleet_service_type'); ?>"},
            {title: "<?php echo app_lang('fleet_service_date'); ?>"},
            {title: "<?php echo app_lang('fleet_mileage_at_service'); ?>"},
            {title: "<?php echo app_lang('fleet_service_cost'); ?>"},
            {title: "<?php echo app_lang('fleet_service_provider'); ?>"},
            {title: "<?php echo app_lang('fleet_next_service'); ?>"},
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
        ]
    });

    $("#fleet-search").on("keyup", function () {
        $("#service-logs-table").appTable({reload: true});
    });
});
</script>
