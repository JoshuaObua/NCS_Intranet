<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('create_an_appointment'); ?></h1>
        </div>
        <div class="card-body p30">
            <?php echo form_open(get_uri("visitor_appointment/save"), array("id" => "visitor-appointment-page-form", "class" => "general-form", "role" => "form")); ?>
            
            <!-- Type Selector -->
            <div class="form-group mb15">
                <label for="appointment_type" class="col-md-3 control-label"><?php echo app_lang('appointment_type'); ?></label>
                <div class="col-md-9">
                    <div class="form-check form-check-inline me-3">
                        <input class="form-check-input appointment-type-toggle" type="radio" name="appointment_type" id="type_external_page" value="external" checked>
                        <label class="form-check-label" for="type_external_page"><?php echo app_lang('external_visitor'); ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input appointment-type-toggle" type="radio" name="appointment_type" id="type_internal_page" value="internal">
                        <label class="form-check-label" for="type_internal_page"><?php echo app_lang('internal_staff'); ?></label>
                    </div>
                </div>
            </div>

            <!-- External Fields Section -->
            <div id="external-visitor-fields-page">
                <div class="row">
                    <div class="col-md-4 form-group mb15">
                        <label for="first_name" class="control-label"><?php echo app_lang('first_name'); ?> *</label>
                        <input type="text" name="first_name" id="first_name_page" class="form-control" placeholder="<?php echo app_lang('first_name'); ?>" data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" />
                    </div>
                    <div class="col-md-4 form-group mb15">
                        <label for="last_name" class="control-label"><?php echo app_lang('last_name'); ?> *</label>
                        <input type="text" name="last_name" id="last_name_page" class="form-control" placeholder="<?php echo app_lang('last_name'); ?>" data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" />
                    </div>
                    <div class="col-md-4 form-group mb15">
                        <label for="other_name" class="control-label"><?php echo app_lang('other_name'); ?></label>
                        <input type="text" name="other_name" id="other_name_page" class="form-control" placeholder="<?php echo app_lang('other_name'); ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb15">
                        <label for="id_type" class="control-label"><?php echo app_lang('id_type'); ?></label>
                        <select name="id_type" id="id_type_page" class="form-control select2">
                            <option value="nin"><?php echo app_lang('nin'); ?></option>
                            <option value="passport"><?php echo app_lang('passport'); ?></option>
                            <option value="driving_license"><?php echo app_lang('driving_license'); ?></option>
                            <option value="refugee_card"><?php echo app_lang('refugee_card'); ?></option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group mb15">
                        <label for="id_number" class="control-label"><?php echo app_lang('id_number'); ?></label>
                        <input type="text" name="id_number" id="id_number_page" class="form-control" placeholder="<?php echo app_lang('id_number'); ?>" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group mb15">
                        <label for="phone" class="control-label"><?php echo app_lang('phone'); ?></label>
                        <input type="text" name="phone" id="phone_page" class="form-control" placeholder="<?php echo app_lang('phone'); ?>" />
                    </div>
                    <div class="col-md-4 form-group mb15">
                        <label for="email" class="control-label"><?php echo app_lang('email'); ?></label>
                        <input type="email" name="email" id="email_page" class="form-control" placeholder="<?php echo app_lang('email'); ?>" />
                    </div>
                    <div class="col-md-4 form-group mb15">
                        <label for="organization" class="control-label"><?php echo app_lang('organization'); ?></label>
                        <input type="text" name="organization" id="organization_page" class="form-control" placeholder="<?php echo app_lang('organization'); ?>" />
                    </div>
                </div>
            </div>

            <!-- Common Fields -->
            <div class="form-group mb15">
                <label for="to_user_id" class="control-label"><?php echo app_lang('person_to_visit'); ?> *</label>
                <?php echo form_dropdown("to_user_id", $team_members_dropdown, array(), "class='form-control select2' id='to_user_id_page' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"); ?>
            </div>

            <div class="row">
                <div class="col-md-6 form-group mb15">
                    <label for="appointment_date" class="control-label"><?php echo app_lang('appointment_date'); ?> *</label>
                    <input type="text" name="appointment_date" id="appointment_date_page" class="form-control date-picker" placeholder="YYYY-MM-DD" data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" />
                </div>
                <div class="col-md-6 form-group mb15">
                    <label for="appointment_time" class="control-label"><?php echo app_lang('appointment_time'); ?> *</label>
                    <input type="time" name="appointment_time" id="appointment_time_page" class="form-control" value="09:00" data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" />
                </div>
            </div>

            <div class="form-group mb15">
                <label for="reason" class="control-label"><?php echo app_lang('reason_for_visit'); ?> *</label>
                <textarea name="reason" id="reason_page" class="form-control" rows="4" placeholder="<?php echo app_lang('reason_for_visit'); ?>..." data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>"></textarea>
            </div>

            <div class="form-group mb15">
                <label class="control-label"><?php echo app_lang('files'); ?></label>
                <div>
                    <?php echo view("includes/dropzone_preview"); ?>
                </div>
            </div>

            <div class="mt20 clearfix">
                <?php echo view("includes/upload_button"); ?>
                <button type="submit" class="btn btn-primary float-end"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#visitor-appointment-page-form").appForm({
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 5000});
                window.location.href = "<?php echo get_uri('visitor_appointment'); ?>";
            }
        });

        $(".select2").select2();

        setDatePicker("#appointment_date_page");

        $(".appointment-type-toggle").change(function () {
            if ($(this).val() === "internal") {
                $("#external-visitor-fields-page").addClass("hide");
                $("#first_name_page, #last_name_page").removeAttr("data-rule-required");
            } else {
                $("#external-visitor-fields-page").removeClass("hide");
                $("#first_name_page, #last_name_page").attr("data-rule-required", "true");
            }
        });
    });
</script>
