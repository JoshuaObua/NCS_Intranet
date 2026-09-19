<?php echo form_open(get_uri("engineering/save_civil_asset_verification"), array("id" => "verify-civil-asset-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="asset_name">Asset / Parcel Title <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "asset_name", "name" => "asset_name", "value" => $model_info->asset_name, "class" => "form-control", "placeholder" => "e.g. Plot 2-10 Coronation Avenue Land", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="title_deed_no">Title Deed / Folio No</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "title_deed_no", "name" => "title_deed_no", "value" => $model_info->title_deed_no, "class" => "form-control", "placeholder" => "e.g. FRV 412 Folio 19")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="parcel_location">Cadastral Location / Address <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "parcel_location", "name" => "parcel_location", "value" => $model_info->parcel_location, "class" => "form-control", "placeholder" => "e.g. Lugogo Sports Complex", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="acreage_ha">Area / Acreage (Hectares)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "acreage_ha", "name" => "acreage_ha", "value" => $model_info->acreage_ha ?: "0.00", "class" => "form-control", "type" => "number", "step" => "0.01")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="valuation_ugx">Valuation (UGX)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "valuation_ugx", "name" => "valuation_ugx", "value" => $model_info->valuation_ugx ?: "0.00", "class" => "form-control", "type" => "number", "step" => "0.01")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="boundary_status">Boundary Status</label>
                <div class="col-md-9">
                    <select name="boundary_status" id="boundary_status" class="form-control select2">
                        <option value="VERIFIED" <?php echo $model_info->boundary_status === "VERIFIED" || !$model_info->boundary_status ? "selected" : ""; ?>>Verified & Beacons Placed</option>
                        <option value="IN_PROGRESS" <?php echo $model_info->boundary_status === "IN_PROGRESS" ? "selected" : ""; ?>>Re-surveying In Progress</option>
                        <option value="UNVERIFIED" <?php echo $model_info->boundary_status === "UNVERIFIED" ? "selected" : ""; ?>>Unverified / Beacon Missing</option>
                        <option value="DISPUTED" <?php echo $model_info->boundary_status === "DISPUTED" ? "selected" : ""; ?>>Boundary Dispute Pending</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="encroachment_status">Encroachment Status</label>
                <div class="col-md-9">
                    <select name="encroachment_status" id="encroachment_status" class="form-control select2">
                        <option value="CLEAR" <?php echo $model_info->encroachment_status === "CLEAR" || !$model_info->encroachment_status ? "selected" : ""; ?>>Clear & Fully Secure</option>
                        <option value="HIGH_RISK" <?php echo $model_info->encroachment_status === "HIGH_RISK" ? "selected" : ""; ?>>High Risk Buffer Zone</option>
                        <option value="ENCROACHED" <?php echo $model_info->encroachment_status === "ENCROACHED" ? "selected" : ""; ?>>Encroached (Squatters / Unauthorized Structures)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="last_survey_date">Last Cadastral Survey Date</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "last_survey_date", "name" => "last_survey_date", "value" => $model_info->last_survey_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="surveyor_name">Registered Surveyor / Assessor</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "surveyor_name", "name" => "surveyor_name", "value" => $model_info->surveyor_name, "class" => "form-control", "placeholder" => "e.g. Ministry of Lands / Staff Surveyor")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="notes">Cadastral & Civil Notes</label>
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
        $("#boundary_status, #encroachment_status").select2();
        setDatePicker("#last_survey_date");

        $("#verify-civil-asset-form").appForm({
            onSuccess: function (result) {
                $("#civil-assets-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
