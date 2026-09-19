<?php echo form_open(get_uri("fleet/save_route"), array("id" => "fleet-route-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="title"><?php echo app_lang('title'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "title", "name" => "title", "value" => $model_info->title, "class" => "form-control", "placeholder" => app_lang("title"), "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="vehicle_id"><?php echo app_lang('vehicle'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_dropdown("vehicle_id", $vehicle_dropdown, $model_info->vehicle_id, "id='vehicle_id' class='select2' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="assigned_driver"><?php echo app_lang('fleet_driver'); ?></label>
                <div class="col-md-9">
                    <?php echo form_dropdown("assigned_driver", $driver_dropdown, $model_info->assigned_driver, "id='assigned_driver' class='select2'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="start_location"><?php echo app_lang('fleet_start_location'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "start_location", "name" => "start_location", "value" => $model_info->start_location, "class" => "form-control", "placeholder" => app_lang("fleet_start_location"), "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="end_location"><?php echo app_lang('fleet_end_location'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "end_location", "name" => "end_location", "value" => $model_info->end_location, "class" => "form-control", "placeholder" => app_lang("fleet_end_location"), "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="waypoints"><?php echo app_lang('fleet_waypoints'); ?></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "waypoints", "name" => "waypoints", "value" => $model_info->waypoints, "class" => "form-control", "rows" => 2, "placeholder" => app_lang("fleet_waypoints_placeholder"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="distance_km"><?php echo app_lang('fleet_distance_km'); ?></label>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "distance_km", "name" => "distance_km", "value" => $model_info->distance_km ?: "", "class" => "form-control", "type" => "number", "step" => "0.1", "min" => "0")); ?>
                </div>
                <label class="col-md-2" for="estimated_duration"><?php echo app_lang('fleet_duration'); ?></label>
                <div class="col-md-4">
                    <?php echo form_input(array("id" => "estimated_duration", "name" => "estimated_duration", "value" => $model_info->estimated_duration, "class" => "form-control", "placeholder" => "e.g. 2h 30m")); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="scheduled_date"><?php echo app_lang('fleet_scheduled_date'); ?></label>
                <div class="col-md-4">
                    <?php echo form_input(array("id" => "scheduled_date", "name" => "scheduled_date", "value" => $model_info->scheduled_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
                <label class="col-md-2" for="status"><?php echo app_lang('status'); ?></label>
                <div class="col-md-3">
                    <?php echo form_dropdown("status", $status_dropdown, $model_info->status ?: "planned", "id='status' class='select2'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="notes"><?php echo app_lang('notes'); ?></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "notes", "name" => "notes", "value" => $model_info->notes, "class" => "form-control", "rows" => 3)); ?>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>
<script>
    $(document).ready(function () {
        $("#fleet-route-form").appForm({
            onSuccess: function (result) {
                $("#routes-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#vehicle_id, #assigned_driver, #status").select2();
        setDatePicker("#scheduled_date");
    });
</script>
