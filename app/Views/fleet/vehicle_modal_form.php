<?php echo form_open(get_uri("fleet/save_vehicle"), array("id" => "fleet-vehicle-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="plate_number"><?php echo app_lang('plate_number'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "plate_number", "name" => "plate_number", "value" => $model_info->plate_number, "class" => "form-control", "placeholder" => app_lang("plate_number"), "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="vin"><?php echo app_lang('vin'); ?></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "vin", "name" => "vin", "value" => $model_info->vin, "class" => "form-control", "placeholder" => "e.g. 1HGBH41JXMN109186", "maxlength" => "17")); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="make"><?php echo app_lang('fleet_make'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "make", "name" => "make", "value" => $model_info->make, "class" => "form-control", "placeholder" => app_lang("fleet_make"), "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="model"><?php echo app_lang('fleet_model'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "model", "name" => "model", "value" => $model_info->model, "class" => "form-control", "placeholder" => app_lang("fleet_model"), "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="year"><?php echo app_lang('fleet_year'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "year", "name" => "year", "value" => $model_info->year ?: date("Y"), "class" => "form-control", "type" => "number", "min" => "1900", "max" => date("Y") + 1, "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
                <label class="col-md-2" for="color"><?php echo app_lang('fleet_color'); ?></label>
                <div class="col-md-4">
                    <?php echo form_input(array("id" => "color", "name" => "color", "value" => $model_info->color, "class" => "form-control", "placeholder" => app_lang("fleet_color"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="fuel_type"><?php echo app_lang('fleet_fuel_type'); ?></label>
                <div class="col-md-4">
                    <?php echo form_dropdown("fuel_type", $fuel_type_dropdown, $model_info->fuel_type, "id='fuel_type' class='select2'"); ?>
                </div>
                <label class="col-md-2" for="status"><?php echo app_lang('status'); ?></label>
                <div class="col-md-3">
                    <?php echo form_dropdown("status", $status_dropdown, $model_info->status, "id='status' class='select2'"); ?>
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
                <label class="col-md-3" for="mileage"><?php echo app_lang('fleet_mileage'); ?> (km)</label>
                <div class="col-md-4">
                    <?php echo form_input(array("id" => "mileage", "name" => "mileage", "value" => $model_info->mileage ?: 0, "class" => "form-control", "type" => "number", "min" => "0")); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="last_service_date"><?php echo app_lang('fleet_last_service'); ?></label>
                <div class="col-md-4">
                    <?php echo form_input(array("id" => "last_service_date", "name" => "last_service_date", "value" => $model_info->last_service_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
                <label class="col-md-2" for="next_service_date"><?php echo app_lang('fleet_next_service'); ?></label>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "next_service_date", "name" => "next_service_date", "value" => $model_info->next_service_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
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
        $("#fleet-vehicle-form").appForm({
            onSuccess: function (result) {
                $("#fleet-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#fuel_type, #status, #assigned_driver").select2();
        setDatePicker("#last_service_date, #next_service_date");
    });
</script>
