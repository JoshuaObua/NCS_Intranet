<?php echo form_open(get_uri("hr_memos/save"), array("id" => "memo-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="subject"><?php echo app_lang('subject'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "subject", "name" => "subject", "value" => $model_info->subject, "class" => "form-control", "placeholder" => app_lang("subject"), "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="priority"><?php echo app_lang('priority'); ?></label>
                <div class="col-md-4">
                    <?php echo form_dropdown("priority", $priority_dropdown, $model_info->priority ?: "normal", "id='priority' class='select2'"); ?>
                </div>
                <label class="col-md-2" for="target_type"><?php echo app_lang('recipients'); ?></label>
                <div class="col-md-3">
                    <?php echo form_dropdown("target_type", $target_dropdown, $model_info->target_type ?: "all", "id='target_type' class='select2'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group" id="department-row" style="display:none;">
            <div class="row">
                <label class="col-md-3" for="department"><?php echo app_lang('hr_department'); ?></label>
                <div class="col-md-9">
                    <select name="department" id="department" class="form-control select2">
                        <option value=""><?php echo "-- " . app_lang("select") . " --"; ?></option>
                        <?php foreach ($departments as $d) { ?>
                            <option value="<?php echo $d->department; ?>" <?php echo ($model_info->department == $d->department) ? "selected" : ""; ?>>
                                <?php echo $d->department; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group" id="individuals-row" style="display:none;">
            <div class="row">
                <label class="col-md-3" for="recipient_ids"><?php echo app_lang('team_members'); ?></label>
                <div class="col-md-9">
                    <select name="recipient_ids[]" id="recipient_ids" class="form-control select2" multiple>
                        <?php foreach ($users_dropdown as $uid => $uname) { ?>
                            <option value="<?php echo $uid; ?>"><?php echo $uname; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="body"><?php echo app_lang('message'); ?> <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "body", "name" => "body", "value" => $model_info->body, "class" => "form-control", "rows" => 8, "data-rule-required" => true, "data-msg-required" => app_lang("field_required"))); ?>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="send" class="icon-16"></span> <?php echo app_lang('send'); ?></button>
</div>
<?php echo form_close(); ?>
<script>
$(document).ready(function () {
    function toggleRecipientFields() {
        var target = $("#target_type").val();
        $("#department-row").toggle(target === "department");
        $("#individuals-row").toggle(target === "individual");
    }
    toggleRecipientFields();
    $("#target_type").on("change", toggleRecipientFields);

    $("#memo-form").appForm({
        onSuccess: function (result) {
            if (typeof appTable !== "undefined") { appTable.reload(); }
            $("#memos-table").appTable({reload: true});
        }
    });
});
</script>
