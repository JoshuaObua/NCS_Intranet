<?php echo form_open(get_uri("hr_dept_reports/save_template"), array("id" => "template-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="title"><?php echo app_lang('title'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "title", "name" => "title", "value" => $model_info->title, "class" => "form-control", "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="frequency"><?php echo app_lang('frequency'); ?></label>
                <div class="col-md-4">
                    <?php echo form_dropdown("frequency", $frequency_dropdown, $model_info->frequency, "id='frequency' class='select2'"); ?>
                </div>
                <label class="col-md-2" for="is_active"><?php echo app_lang('active'); ?></label>
                <div class="col-md-3 mt5">
                    <input type="checkbox" name="is_active" value="1" id="is_active" <?php echo ($model_info->is_active || !$model_info->id) ? "checked" : ""; ?> />
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="department"><?php echo app_lang('hr_department'); ?></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "department", "name" => "department", "value" => $model_info->department ?: "ALL", "class" => "form-control", "placeholder" => "ALL or specific dept")); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="description"><?php echo app_lang('description'); ?></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "description", "name" => "description", "value" => $model_info->description, "class" => "form-control", "rows" => 2)); ?>
                </div>
            </div>
        </div>

        <hr/>
        <h6><?php echo app_lang("fields"); ?></h6>
        <div id="fields-container">
            <?php foreach ($fields as $i => $field) { ?>
            <div class="field-row mb10 p10 border rounded">
                <div class="row">
                    <div class="col-md-4">
                        <?php echo form_input(array("name" => "fields[{$i}][label]", "value" => $field->label, "class" => "form-control", "placeholder" => app_lang("label"))); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo form_dropdown("fields[{$i}][field_type]", $field_type_dropdown, $field->field_type, "class='select2'"); ?>
                    </div>
                    <div class="col-md-3">
                        <label class="mt5"><input type="checkbox" name="fields[<?php echo $i; ?>][is_required]" value="1" <?php echo $field->is_required ? "checked" : ""; ?> /> <?php echo app_lang("required"); ?></label>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-xs btn-danger remove-field-row"><i data-feather="x" class="icon-14"></i></button>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <button type="button" id="add-field-row" class="btn btn-sm btn-default mt5"><i data-feather="plus" class="icon-14"></i> <?php echo app_lang("add_field"); ?></button>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>
<script>
$(document).ready(function () {
    $("#template-form").appForm({
        onSuccess: function () { $("#templates-table").appTable({reload: true}); }
    });

    var fieldIndex = <?php echo max(count($fields), 0); ?>;
    var fieldTypeOptions = <?php echo json_encode($field_type_dropdown); ?>;

    $("#add-field-row").on("click", function () {
        var opts = "";
        $.each(fieldTypeOptions, function (k, v) { opts += '<option value="' + k + '">' + v + '</option>'; });
        var row = '<div class="field-row mb10 p10 border rounded"><div class="row">' +
            '<div class="col-md-4"><input type="text" name="fields[' + fieldIndex + '][label]" class="form-control" placeholder="<?php echo app_lang('label'); ?>" /></div>' +
            '<div class="col-md-3"><select name="fields[' + fieldIndex + '][field_type]" class="form-control select2">' + opts + '</select></div>' +
            '<div class="col-md-3"><label class="mt5"><input type="checkbox" name="fields[' + fieldIndex + '][is_required]" value="1" /> <?php echo app_lang('required'); ?></label></div>' +
            '<div class="col-md-2"><button type="button" class="btn btn-xs btn-danger remove-field-row"><i data-feather="x" class="icon-14"></i></button></div>' +
            '</div></div>';
        $("#fields-container").append(row);
        fieldIndex++;
        feather.replace();
        $(".select2").select2();
    });

    $(document).on("click", ".remove-field-row", function () { $(this).closest(".field-row").remove(); });
});
</script>
