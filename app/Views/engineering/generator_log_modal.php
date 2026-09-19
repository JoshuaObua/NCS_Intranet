<?php echo form_open(get_uri("engineering/save_generator_log"), array("id" => "generator-log-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="equipment_name">Equipment Name <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "equipment_name", "name" => "equipment_name", "value" => $model_info->equipment_name, "class" => "form-control", "placeholder" => "e.g. 60 KVA Perkins Diesel Standby Generator", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="equipment_type">Equipment Category</label>
                <div class="col-md-9">
                    <select name="equipment_type" id="equipment_type" class="form-control select2">
                        <option value="GENERATOR" <?php echo $model_info->equipment_type === "GENERATOR" || !$model_info->equipment_type ? "selected" : ""; ?>>Diesel Generator & ATS</option>
                        <option value="HVAC_AC" <?php echo $model_info->equipment_type === "HVAC_AC" ? "selected" : ""; ?>>HVAC & Cassette Air Conditioner</option>
                        <option value="TRANSFORMER" <?php echo $model_info->equipment_type === "TRANSFORMER" ? "selected" : ""; ?>>Step-down Transformer & High Voltage Substation</option>
                        <option value="FLOODLIGHT_PANEL" <?php echo $model_info->equipment_type === "FLOODLIGHT_PANEL" ? "selected" : ""; ?>>Stadium Floodlight Switchgear Panel</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="rating_kva_or_kw">Rating Capacity (kVA / kW)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "rating_kva_or_kw", "name" => "rating_kva_or_kw", "value" => $model_info->rating_kva_or_kw, "class" => "form-control", "placeholder" => "e.g. 60 kVA / 48 kW")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="facility_location">Facility Location <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "facility_location", "name" => "facility_location", "value" => $model_info->facility_location, "class" => "form-control", "placeholder" => "e.g. Lugogo Main Power House", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="fuel_tank_capacity_l">Tank Capacity (Liters)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "fuel_tank_capacity_l", "name" => "fuel_tank_capacity_l", "value" => $model_info->fuel_tank_capacity_l ?: "200", "class" => "form-control", "type" => "number", "step" => "0.1")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="fuel_level_percent">Current Fuel Level (%)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "fuel_level_percent", "name" => "fuel_level_percent", "value" => $model_info->fuel_level_percent ?: "100", "class" => "form-control", "type" => "number", "min" => "0", "max" => "100", "step" => "1")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="runtime_hours">Cumulative Runtime (Hours)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "runtime_hours", "name" => "runtime_hours", "value" => $model_info->runtime_hours ?: "0.0", "class" => "form-control", "type" => "number", "step" => "0.1")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="ats_status">Automatic Transfer Switch (ATS)</label>
                <div class="col-md-9">
                    <select name="ats_status" id="ats_status" class="form-control select2">
                        <option value="PASS_AUTO" <?php echo $model_info->ats_status === "PASS_AUTO" || !$model_info->ats_status ? "selected" : ""; ?>>Pass (Auto Start Ready)</option>
                        <option value="ATTENTION" <?php echo $model_info->ats_status === "ATTENTION" ? "selected" : ""; ?>>Needs Battery / Sensor Service</option>
                        <option value="MANUAL_ONLY" <?php echo $model_info->ats_status === "MANUAL_ONLY" ? "selected" : ""; ?>>Manual Transfer Only</option>
                        <option value="FAULTY" <?php echo $model_info->ats_status === "FAULTY" ? "selected" : ""; ?>>Faulty (Bypassed)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="last_service_date">Last Service Date</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "last_service_date", "name" => "last_service_date", "value" => $model_info->last_service_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="notes">Telemetry & Maintenance Notes</label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "notes", "name" => "notes", "value" => $model_info->notes, "class" => "form-control", "rows" => 2)); ?>
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
        $("#equipment_type, #ats_status").select2();
        setDatePicker("#last_service_date");

        $("#generator-log-form").appForm({
            onSuccess: function (result) {
                $("#electrical-assets-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
