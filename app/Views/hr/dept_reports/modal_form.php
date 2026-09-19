<?php echo form_open(get_uri("hr_dept_reports/save"), array("id" => "dept-report-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="template_id"><?php echo app_lang('template'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_dropdown("template_id", $templates_dropdown, $model_info->template_id, "id='template_id' class='select2' data-rule-required=true data-msg-required='" . app_lang('field_required') . "'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="department"><?php echo app_lang('hr_department'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-5">
                    <?php echo form_input(array("id" => "department", "name" => "department", "value" => $model_info->department, "class" => "form-control", "placeholder" => app_lang("hr_department"), "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
                <label class="col-md-1" for="period_label"><?php echo app_lang('period'); ?></label>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "period_label", "name" => "period_label", "value" => $model_info->period_label, "class" => "form-control", "placeholder" => "e.g. Jan 2026")); ?>
                </div>
            </div>
        </div>

        <!-- Dynamic fields loaded by template -->
        <div id="template-fields-area">
            <?php if (!empty($fields)) { ?>
                <?php foreach ($fields as $field) { ?>
                <div class="form-group">
                    <div class="row">
                        <label class="col-md-3"><?php echo $field->label; ?> <?php if ($field->is_required) { ?><span class="text-danger">*</span><?php } ?></label>
                        <div class="col-md-9">
                            <?php
                            $val = $field_data[$field->id] ?? "";
                            if ($field->field_type === "textarea") {
                                echo form_textarea(array("name" => "field_values[{$field->id}]", "value" => $val, "class" => "form-control", "rows" => 3));
                            } elseif ($field->field_type === "date") {
                                echo form_input(array("name" => "field_values[{$field->id}]", "value" => $val, "class" => "form-control datepicker", "autocomplete" => "off"));
                            } elseif ($field->field_type === "number") {
                                echo form_input(array("name" => "field_values[{$field->id}]", "value" => $val, "class" => "form-control", "type" => "number"));
                            } else {
                                echo form_input(array("name" => "field_values[{$field->id}]", "value" => $val, "class" => "form-control"));
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            <?php } ?>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="notes"><?php echo app_lang('notes'); ?></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "notes", "name" => "notes", "value" => $model_info->notes, "class" => "form-control", "rows" => 3)); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="status"><?php echo app_lang('status'); ?></label>
                <div class="col-md-4">
                    <select name="status" class="form-control select2">
                        <option value="draft" <?php echo (!$model_info->status || $model_info->status == "draft") ? "selected" : ""; ?>><?php echo app_lang("hr_draft"); ?></option>
                        <option value="submitted" <?php echo $model_info->status == "submitted" ? "selected" : ""; ?>><?php echo app_lang("hr_submitted"); ?></option>
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
    $("#dept-report-form").appForm({
        onSuccess: function () { $("#reports-table").appTable({reload: true}); }
    });
    $(".datepicker").datepicker();

    $("#template_id").on("change", function () {
        var tid = $(this).val();
        if (!tid) { $("#template-fields-area").html(""); return; }
        $.post(get_uri("hr_dept_reports/get_template_fields"), {template_id: tid}, function (data) {
            if (data.success && data.fields.length) {
                var html = "";
                $.each(data.fields, function (i, f) {
                    var input = '<input type="text" name="field_values[' + f.id + ']" class="form-control" />';
                    if (f.field_type === "textarea") input = '<textarea name="field_values[' + f.id + ']" class="form-control" rows="3"></textarea>';
                    if (f.field_type === "number") input = '<input type="number" name="field_values[' + f.id + ']" class="form-control" />';
                    html += '<div class="form-group"><div class="row"><label class="col-md-3">' + f.label + '</label><div class="col-md-9">' + input + '</div></div></div>';
                });
                $("#template-fields-area").html(html);
            } else {
                $("#template-fields-area").html("");
            }
        }, "json");
    });
});
</script>
