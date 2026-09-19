<?php echo form_open(get_uri("visitor_logbook/save"), array("id" => "visitor-logbook-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : ''; ?>" />

        <!-- Nationality Selector -->
        <div class="form-group mb15">
            <div class="row">
                <label for="nationality" class="col-md-3 control-label"><?php echo app_lang('nationality'); ?> *</label>
                <div class="col-md-9">
                    <div class="form-check form-check-inline me-3">
                        <input class="form-check-input nationality-toggle" type="radio" name="nationality" id="nat_ug" value="ugandan" <?php echo (!$model_info || $model_info->nationality === 'ugandan') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="nat_ug"><?php echo app_lang('ugandan'); ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input nationality-toggle" type="radio" name="nationality" id="nat_non_ug" value="non_ugandan" <?php echo ($model_info && $model_info->nationality === 'non_ugandan') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="nat_non_ug"><?php echo app_lang('non_ugandan'); ?></label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visitor Names -->
        <div class="row">
            <div class="col-md-4 form-group mb15">
                <label for="first_name" class="control-label"><?php echo app_lang('first_name'); ?> *</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo $model_info ? $model_info->first_name : ''; ?>" class="form-control" placeholder="<?php echo app_lang('first_name'); ?>" data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" />
            </div>
            <div class="col-md-4 form-group mb15">
                <label for="last_name" class="control-label"><?php echo app_lang('last_name'); ?> *</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo $model_info ? $model_info->last_name : ''; ?>" class="form-control" placeholder="<?php echo app_lang('last_name'); ?>" data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" />
            </div>
            <div class="col-md-4 form-group mb15">
                <label for="other_name" class="control-label"><?php echo app_lang('other_name'); ?></label>
                <input type="text" name="other_name" id="other_name" value="<?php echo $model_info ? $model_info->other_name : ''; ?>" class="form-control" placeholder="<?php echo app_lang('other_name'); ?>" />
            </div>
        </div>

        <!-- Dynamic ID Section -->
        <div class="row">
            <div class="col-md-6 form-group mb15">
                <label for="id_type" class="control-label"><?php echo app_lang('id_type'); ?> *</label>
                <div id="ugandan-id-wrapper" class="<?php echo ($model_info && $model_info->nationality === 'non_ugandan') ? 'hide' : ''; ?>">
                    <input type="text" class="form-control" value="National ID (NIN)" readonly />
                    <input type="hidden" name="id_type" value="nin" />
                </div>
                <div id="non-ugandan-id-wrapper" class="<?php echo (!$model_info || $model_info->nationality === 'ugandan') ? 'hide' : ''; ?>">
                    <select name="id_type" id="id_type" class="form-control select2">
                        <option value="passport" <?php echo ($model_info && $model_info->id_type === 'passport') ? 'selected' : ''; ?>><?php echo app_lang('passport'); ?></option>
                        <option value="driving_license" <?php echo ($model_info && $model_info->id_type === 'driving_license') ? 'selected' : ''; ?>><?php echo app_lang('driving_license'); ?></option>
                        <option value="refugee_card" <?php echo ($model_info && $model_info->id_type === 'refugee_card') ? 'selected' : ''; ?>><?php echo app_lang('refugee_card'); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 form-group mb15">
                <label for="id_number" class="control-label"><?php echo app_lang('id_number'); ?> *</label>
                <input type="text" name="id_number" id="id_number" value="<?php echo $model_info ? $model_info->id_number : ''; ?>" class="form-control" placeholder="<?php echo app_lang('id_number'); ?>" data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" />
            </div>
        </div>

        <!-- Contact & Organization -->
        <div class="row">
            <div class="col-md-4 form-group mb15">
                <label for="phone" class="control-label"><?php echo app_lang('phone'); ?></label>
                <input type="text" name="phone" id="phone" value="<?php echo $model_info ? $model_info->phone : ''; ?>" class="form-control" placeholder="<?php echo app_lang('phone'); ?>" />
            </div>
            <div class="col-md-4 form-group mb15">
                <label for="email" class="control-label"><?php echo app_lang('email'); ?></label>
                <input type="email" name="email" id="email" value="<?php echo $model_info ? $model_info->email : ''; ?>" class="form-control" placeholder="<?php echo app_lang('email'); ?>" />
            </div>
            <div class="col-md-4 form-group mb15">
                <label for="organization" class="control-label"><?php echo app_lang('organization'); ?></label>
                <input type="text" name="organization" id="organization" value="<?php echo $model_info ? $model_info->organization : ''; ?>" class="form-control" placeholder="<?php echo app_lang('organization'); ?>" />
            </div>
        </div>

        <!-- Entry Gate & Officer to Visit -->
        <div class="row">
            <div class="col-md-6 form-group mb15">
                <label for="gate_name" class="control-label"><?php echo app_lang('gate_name'); ?> *</label>
                <?php echo form_dropdown("gate_name", $gates_dropdown, array($model_info ? $model_info->gate_name : "Main Gate - Lugogo"), "class='form-control select2' id='gate_name'"); ?>
            </div>
            <div class="col-md-6 form-group mb15">
                <label for="to_user_id" class="control-label"><?php echo app_lang('person_to_visit'); ?> *</label>
                <?php echo form_dropdown("to_user_id", $team_members_dropdown, array($model_info ? $model_info->to_user_id : ""), "class='form-control select2' id='to_user_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"); ?>
            </div>
        </div>

        <div class="form-group mb15">
            <label for="reason" class="control-label"><?php echo app_lang('reason_for_visit'); ?> *</label>
            <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="<?php echo app_lang('reason_for_visit'); ?>..." data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>"><?php echo $model_info ? $model_info->reason : ''; ?></textarea>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <div class="col-md-12">
                    <?php echo view("includes/file_list", array("files" => $model_info ? $model_info->files : "")); ?>
                </div>
            </div>
        </div>

        <?php echo view("includes/dropzone_preview"); ?>
    </div>
</div>

<div class="modal-footer">
    <?php echo view("includes/upload_button"); ?>
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#visitor-logbook-form").appForm({
            onSuccess: function (result) {
                $("#visitor-logbook-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#visitor-logbook-form .select2").select2();

        $(".nationality-toggle").change(function () {
            if ($(this).val() === "ugandan") {
                $("#ugandan-id-wrapper").removeClass("hide");
                $("#non-ugandan-id-wrapper").addClass("hide");
            } else {
                $("#ugandan-id-wrapper").addClass("hide");
                $("#non-ugandan-id-wrapper").removeClass("hide");
            }
        });
    });
</script>
