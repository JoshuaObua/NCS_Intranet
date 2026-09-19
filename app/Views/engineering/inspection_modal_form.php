<?php echo form_open(get_uri("engineering/save_inspection"), array("id" => "engineering-inspection-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="event_or_facility">Event / Facility Name <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "event_or_facility", "name" => "event_or_facility", "value" => $model_info->event_or_facility, "class" => "form-control", "placeholder" => "e.g. Uganda Cranes vs Kenya International Match", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="inspection_date">Inspection Date <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "inspection_date", "name" => "inspection_date", "value" => $model_info->inspection_date ?: date("Y-m-d"), "class" => "form-control datepicker", "autocomplete" => "off", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="civil_safety_status">Civil & Structural Clearance</label>
                <div class="col-md-9">
                    <select name="civil_safety_status" id="civil_safety_status" class="form-control select2">
                        <option value="PASS" <?php echo $model_info->civil_safety_status === "PASS" || !$model_info->civil_safety_status ? "selected" : ""; ?>>Pass (Cleared)</option>
                        <option value="CONDITIONAL" <?php echo $model_info->civil_safety_status === "CONDITIONAL" ? "selected" : ""; ?>>Conditional Clearance</option>
                        <option value="FAIL" <?php echo $model_info->civil_safety_status === "FAIL" ? "selected" : ""; ?>>Fail (Safety Hazard)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="electrical_safety_status">Electrical & Power Clearance</label>
                <div class="col-md-9">
                    <select name="electrical_safety_status" id="electrical_safety_status" class="form-control select2">
                        <option value="PASS" <?php echo $model_info->electrical_safety_status === "PASS" || !$model_info->electrical_safety_status ? "selected" : ""; ?>>Pass (Cleared)</option>
                        <option value="CONDITIONAL" <?php echo $model_info->electrical_safety_status === "CONDITIONAL" ? "selected" : ""; ?>>Conditional Clearance</option>
                        <option value="FAIL" <?php echo $model_info->electrical_safety_status === "FAIL" ? "selected" : ""; ?>>Fail (Safety Hazard)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="readiness_score">Readiness Audit Score (%)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "readiness_score", "name" => "readiness_score", "value" => $model_info->readiness_score ?: "100.00", "class" => "form-control", "type" => "number", "step" => "0.1", "min" => "0", "max" => "100")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="findings">Inspection Findings</label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "findings", "name" => "findings", "value" => $model_info->findings, "class" => "form-control", "rows" => 2, "placeholder" => "Structural, electrical, or turf inspection observations...")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="action_items">Action Items Before Event</label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "action_items", "name" => "action_items", "value" => $model_info->action_items, "class" => "form-control", "rows" => 2, "placeholder" => "Required maintenance tasks prior to event kickoff...")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="status">Final Certification Status</label>
                <div class="col-md-9">
                    <select name="status" id="status" class="form-control select2">
                        <option value="PASSED" <?php echo $model_info->status === "PASSED" || !$model_info->status ? "selected" : ""; ?>>PASSED (Certified)</option>
                        <option value="ACTION_REQUIRED" <?php echo $model_info->status === "ACTION_REQUIRED" ? "selected" : ""; ?>>ACTION REQUIRED</option>
                        <option value="FAILED" <?php echo $model_info->status === "FAILED" ? "selected" : ""; ?>>FAILED (Unsafe)</option>
                    </select>
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
        $("#civil_safety_status, #electrical_safety_status, #status").select2();
        setDatePicker("#inspection_date");

        $("#engineering-inspection-form").appForm({
            onSuccess: function (result) {
                $("#engineering-inspections-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
