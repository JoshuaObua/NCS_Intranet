<?php echo form_open(get_uri("engineering/save_asset"), array("id" => "engineering-asset-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="asset_name">Asset Name <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "asset_name", "name" => "asset_name", "value" => $model_info->asset_name, "class" => "form-control", "placeholder" => "e.g. 500kVA Diesel Standby Generator", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="category">Category <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <select name="category" id="category" class="form-control select2" required>
                        <option value="BUILDING_STRUCTURE" <?php echo $model_info->category === "BUILDING_STRUCTURE" ? "selected" : ""; ?>>Building & Stadium Structure</option>
                        <option value="ELECTRICAL_PLANT" <?php echo $model_info->category === "ELECTRICAL_PLANT" ? "selected" : ""; ?>>Electrical Plant & Floodlights</option>
                        <option value="SPORT_TURF" <?php echo $model_info->category === "SPORT_TURF" ? "selected" : ""; ?>>Sports Turf & Playing Surfaces</option>
                        <option value="WATER_SYSTEM" <?php echo $model_info->category === "WATER_SYSTEM" ? "selected" : ""; ?>>Water & Irrigation Systems</option>
                        <option value="HVAC_POWER" <?php echo $model_info->category === "HVAC_POWER" ? "selected" : ""; ?>>Generators & HVAC Systems</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="facility_location">Facility Location <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "facility_location", "name" => "facility_location", "value" => $model_info->facility_location, "class" => "form-control", "placeholder" => "e.g. Lugogo Arena / Central Substation", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="condition_rating">Condition Health Rating</label>
                <div class="col-md-9">
                    <select name="condition_rating" id="condition_rating" class="form-control select2">
                        <option value="EXCELLENT" <?php echo $model_info->condition_rating === "EXCELLENT" ? "selected" : ""; ?>>Excellent</option>
                        <option value="GOOD" <?php echo $model_info->condition_rating === "GOOD" || !$model_info->condition_rating ? "selected" : ""; ?>>Good</option>
                        <option value="FAIR" <?php echo $model_info->condition_rating === "FAIR" ? "selected" : ""; ?>>Fair (Maintenance Required)</option>
                        <option value="CRITICAL" <?php echo $model_info->condition_rating === "CRITICAL" ? "selected" : ""; ?>>Critical (Urgent Attention)</option>
                        <option value="DAMAGED" <?php echo $model_info->condition_rating === "DAMAGED" ? "selected" : ""; ?>>Damaged / Out of Service</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="purchase_value">Purchase / Valued Price (UGX)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "purchase_value", "name" => "purchase_value", "value" => $model_info->purchase_value ?: "0.00", "class" => "form-control", "type" => "number", "step" => "0.01")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="last_inspection_date">Last Inspected Date</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "last_inspection_date", "name" => "last_inspection_date", "value" => $model_info->last_inspection_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="next_maintenance_date">Next Maintenance Due</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "next_maintenance_date", "name" => "next_maintenance_date", "value" => $model_info->next_maintenance_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="notes">Engineering Notes</label>
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
        $("#category, #condition_rating").select2();
        setDatePicker("#last_inspection_date, #next_maintenance_date");

        $("#engineering-asset-form").appForm({
            onSuccess: function (result) {
                $("#engineering-assets-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
