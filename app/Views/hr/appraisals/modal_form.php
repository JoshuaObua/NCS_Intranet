<?php echo form_open(get_uri("hr_appraisals/save"), array("id" => "appraisal-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="employee_id"><?php echo app_lang('employee'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_dropdown("employee_id", $users_dropdown, $model_info->employee_id, "id='employee_id' class='select2' data-rule-required=true data-msg-required='" . app_lang('field_required') . "'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="supervisor_id"><?php echo app_lang('supervisor'); ?></label>
                <div class="col-md-9">
                    <?php echo form_dropdown("supervisor_id", $users_dropdown, $model_info->supervisor_id, "id='supervisor_id' class='select2'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="period_name"><?php echo app_lang('period'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-6">
                    <?php echo form_input(array("id" => "period_name", "name" => "period_name", "value" => $model_info->period_name, "class" => "form-control", "placeholder" => "e.g. Q1 2026", "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "period_year", "name" => "period_year", "value" => $model_info->period_year ?: date('Y'), "class" => "form-control", "type" => "number", "min" => "2000", "max" => date('Y') + 1)); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="status"><?php echo app_lang('status'); ?></label>
                <div class="col-md-4">
                    <select name="status" id="status" class="form-control select2">
                        <option value="draft" <?php echo ($model_info->status == "draft" || !$model_info->status) ? "selected" : ""; ?>><?php echo app_lang("hr_draft"); ?></option>
                        <option value="self_submitted" <?php echo $model_info->status == "self_submitted" ? "selected" : ""; ?>><?php echo app_lang("hr_self_submitted"); ?></option>
                        <option value="supervisor_reviewed" <?php echo $model_info->status == "supervisor_reviewed" ? "selected" : ""; ?>><?php echo app_lang("hr_supervisor_reviewed"); ?></option>
                        <option value="completed" <?php echo $model_info->status == "completed" ? "selected" : ""; ?>><?php echo app_lang("hr_completed"); ?></option>
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
    $("#appraisal-form").appForm({
        onSuccess: function () { $("#appraisals-table").appTable({reload: true}); }
    });
});
</script>
