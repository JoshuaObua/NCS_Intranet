<?php echo form_open(get_uri("visitor_appointment/save"), array("id" => "visitor-appointment-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : ''; ?>" />
        
        <!-- Type Selector -->
        <div class="form-group mb15">
            <div class="row">
                <label for="appointment_type" class="col-md-3 control-label"><?php echo app_lang('appointment_type'); ?></label>
                <div class="col-md-9">
                    <div class="form-check form-check-inline me-3">
                        <input class="form-check-input appointment-type-toggle" type="radio" name="appointment_type" id="type_external" value="external" <?php echo (!$model_info || $model_info->appointment_type === 'external') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="type_external"><?php echo app_lang('external_visitor'); ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input appointment-type-toggle" type="radio" name="appointment_type" id="type_internal" value="internal" <?php echo ($model_info && $model_info->appointment_type === 'internal') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="type_internal"><?php echo app_lang('internal_staff'); ?></label>
                    </div>
                </div>
            </div>
        </div>

        <!-- External Fields Section -->
        <div id="external-visitor-fields" class="<?php echo ($model_info && $model_info->appointment_type === 'internal') ? 'hide' : ''; ?>">
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

            <div class="row">
                <div class="col-md-6 form-group mb15">
                    <label for="id_type" class="control-label"><?php echo app_lang('id_type'); ?></label>
                    <select name="id_type" id="id_type" class="form-control select2">
                        <option value="nin" <?php echo ($model_info && $model_info->id_type === 'nin') ? 'selected' : ''; ?>><?php echo app_lang('nin'); ?></option>
                        <option value="passport" <?php echo ($model_info && $model_info->id_type === 'passport') ? 'selected' : ''; ?>><?php echo app_lang('passport'); ?></option>
                        <option value="driving_license" <?php echo ($model_info && $model_info->id_type === 'driving_license') ? 'selected' : ''; ?>><?php echo app_lang('driving_license'); ?></option>
                        <option value="refugee_card" <?php echo ($model_info && $model_info->id_type === 'refugee_card') ? 'selected' : ''; ?>><?php echo app_lang('refugee_card'); ?></option>
                    </select>
                </div>
                <div class="col-md-6 form-group mb15">
                    <label for="id_number" class="control-label"><?php echo app_lang('id_number'); ?></label>
                    <input type="text" name="id_number" id="id_number" value="<?php echo $model_info ? $model_info->id_number : ''; ?>" class="form-control" placeholder="<?php echo app_lang('id_number'); ?>" />
                </div>
            </div>

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
        </div>

        <!-- Common Fields -->
        <div class="form-group mb15">
            <label for="to_user_id" class="control-label"><?php echo app_lang('person_to_visit'); ?> *</label>
            <?php echo form_dropdown("to_user_id", $team_members_dropdown, array($model_info ? $model_info->to_user_id : ""), "class='form-control select2' id='to_user_id' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"); ?>
        </div>

        <div class="row">
            <div class="col-md-6 form-group mb15">
                <label for="appointment_date" class="control-label"><?php echo app_lang('appointment_date'); ?> *</label>
                <input type="text" name="appointment_date" id="appointment_date" value="<?php echo $model_info ? $model_info->appointment_date : ''; ?>" class="form-control date-picker" placeholder="YYYY-MM-DD" data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" />
            </div>
            <div class="col-md-6 form-group mb15">
                <label for="appointment_time" class="control-label"><?php echo app_lang('appointment_time'); ?> *</label>
                <input type="time" name="appointment_time" id="appointment_time" value="<?php echo $model_info ? $model_info->appointment_time : '09:00'; ?>" class="form-control" data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" />
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
        $("#visitor-appointment-form").appForm({
            onSuccess: function (result) {
                $("#visitor-appointments-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#visitor-appointment-form .select2").select2();

        setDatePicker("#appointment_date");

        $(".appointment-type-toggle").change(function () {
            if ($(this).val() === "internal") {
                $("#external-visitor-fields").addClass("hide");
                $("#first_name, #last_name").removeAttr("data-rule-required");
            } else {
                $("#external-visitor-fields").removeClass("hide");
                $("#first_name, #last_name").attr("data-rule-required", "true");
            }
        });
    });
</script>
