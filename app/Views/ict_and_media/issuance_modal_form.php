<?php echo form_open(get_uri("ict_and_media/save_issuance"), array("id" => "ict-issuance-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="equipment_id">Select Equipment <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <select name="equipment_id" id="equipment_id" class="form-control select2" required>
                        <option value="">-- Choose Available Equipment --</option>
                        <?php foreach ($equipment_dropdown as $eq): ?>
                            <option value="<?php echo $eq->id; ?>" <?php echo $model_info->equipment_id == $eq->id ? "selected" : ""; ?>><?php echo $eq->item_name; ?> (<?php echo $eq->item_code; ?> - <?php echo $eq->category; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="recipient_name">Recipient Custodian <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "recipient_name", "name" => "recipient_name", "value" => $model_info->recipient_name, "class" => "form-control", "placeholder" => "e.g. John Baptist (Media Officer)", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="target_department_name">Target Department <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "target_department_name", "name" => "target_department_name", "value" => $model_info->target_department_name, "class" => "form-control", "placeholder" => "e.g. Public Relations & Media / General Secretary Office", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="purpose">Purpose / Event Deployment <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "purpose", "name" => "purpose", "value" => $model_info->purpose, "class" => "form-control", "placeholder" => "e.g. Live Broadcast for National Boxing Championship", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="issue_date">Dispatch Date</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "issue_date", "name" => "issue_date", "value" => $model_info->issue_date ?: date("Y-m-d"), "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="expected_return_date">Expected Return Date</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "expected_return_date", "name" => "expected_return_date", "value" => $model_info->expected_return_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="condition_on_issue">Condition at Dispatch</label>
                <div class="col-md-9">
                    <select name="condition_on_issue" id="condition_on_issue" class="form-control select2">
                        <option value="EXCELLENT" <?php echo $model_info->condition_on_issue === "EXCELLENT" || !$model_info->condition_on_issue ? "selected" : ""; ?>>Excellent (Tested & Fully Operational)</option>
                        <option value="GOOD" <?php echo $model_info->condition_on_issue === "GOOD" ? "selected" : ""; ?>>Good</option>
                        <option value="FAIR" <?php echo $model_info->condition_on_issue === "FAIR" ? "selected" : ""; ?>>Fair</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="remarks">Issuance Notes / Accessories</label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "remarks", "name" => "remarks", "value" => $model_info->remarks, "class" => "form-control", "rows" => 2, "placeholder" => "e.g. Issued with 2x 160GB Memory Cards, Dual Batteries, and Tripod")); ?>
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
        $("#equipment_id, #condition_on_issue").select2();
        setDatePicker("#issue_date, #expected_return_date");

        $("#ict-issuance-form").appForm({
            onSuccess: function (result) {
                $("#ict-issuances-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
