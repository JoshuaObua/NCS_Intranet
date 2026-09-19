<?php echo form_open(get_uri("fleet/save_service_log"), array("id" => "fleet-service-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

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
                <label class="col-md-3" for="service_type"><?php echo app_lang('fleet_service_type'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_dropdown("service_type", $service_type_dropdown, $model_info->service_type, "id='service_type' class='select2' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="service_date"><?php echo app_lang('fleet_service_date'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-4">
                    <?php echo form_input(array("id" => "service_date", "name" => "service_date", "value" => $model_info->service_date ?: date("Y-m-d"), "class" => "form-control datepicker", "autocomplete" => "off", "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
                <label class="col-md-2" for="mileage_at_service"><?php echo app_lang('fleet_mileage'); ?> (km)</label>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "mileage_at_service", "name" => "mileage_at_service", "value" => $model_info->mileage_at_service ?: 0, "class" => "form-control", "type" => "number", "min" => "0")); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="service_provider"><?php echo app_lang('fleet_service_provider'); ?></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "service_provider", "name" => "service_provider", "value" => $model_info->service_provider, "class" => "form-control", "placeholder" => app_lang("fleet_service_provider"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="cost"><?php echo app_lang('fleet_service_cost'); ?></label>
                <div class="col-md-4">
                    <?php echo form_input(array("id" => "cost", "name" => "cost", "value" => $model_info->cost ?: "0.00", "class" => "form-control", "type" => "number", "step" => "0.01", "min" => "0")); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="next_service_date"><?php echo app_lang('fleet_next_service'); ?></label>
                <div class="col-md-4">
                    <?php echo form_input(array("id" => "next_service_date", "name" => "next_service_date", "value" => $model_info->next_service_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
                <label class="col-md-2" for="next_service_mileage"><?php echo app_lang('fleet_next_service_mileage'); ?></label>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "next_service_mileage", "name" => "next_service_mileage", "value" => $model_info->next_service_mileage ?: "", "class" => "form-control", "type" => "number", "min" => "0")); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="description"><?php echo app_lang('description'); ?></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "description", "name" => "description", "value" => $model_info->description, "class" => "form-control", "rows" => 3)); ?>
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
        $("#fleet-service-form").appForm({
            onSuccess: function (result) {
                $("#service-logs-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#vehicle_id, #service_type").select2();
        setDatePicker("#service_date, #next_service_date");
    });
</script>
