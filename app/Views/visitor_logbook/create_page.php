<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('create_visitor_record'); ?></h1>
        </div>
        <div class="card-body p30">
            <?php echo form_open(get_uri("visitor_logbook/save"), array("id" => "visitor-logbook-page-form", "class" => "general-form", "role" => "form")); ?>

            <!-- Nationality Selector -->
            <div class="form-group mb15">
                <label for="nationality" class="col-md-3 control-label"><?php echo app_lang('nationality'); ?> *</label>
                <div class="col-md-9">
                    <div class="form-check form-check-inline me-3">
                        <input class="form-check-input nationality-toggle-page" type="radio" name="nationality" id="nat_ug_page" value="ugandan" checked>
                        <label class="form-check-label" for="nat_ug_page"><?php echo app_lang('ugandan'); ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input nationality-toggle-page" type="radio" name="nationality" id="nat_non_ug_page" value="non_ugandan">
                        <label class="form-check-label" for="nat_non_ug_page"><?php echo app_lang('non_ugandan'); ?></label>
                    </div>
                </div>
            </div>

            <!-- Visitor Names -->
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

            <!-- Dynamic ID Section -->
            <div class="row">
                <div class="col-md-6 form-group mb15">
                    <label for="id_type" class="control-label"><?php echo app_lang('id_type'); ?> *</label>
                    <div id="ugandan-id-wrapper-page">
                        <input type="text" class="form-control" value="National ID (NIN)" readonly />
                        <input type="hidden" name="id_type" value="nin" />
                    </div>
                    <div id="non-ugandan-id-wrapper-page" class="hide">
                        <select name="id_type" id="id_type_page" class="form-control select2">
                            <option value="passport"><?php echo app_lang('passport'); ?></option>
                            <option value="driving_license"><?php echo app_lang('driving_license'); ?></option>
                            <option value="refugee_card"><?php echo app_lang('refugee_card'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 form-group mb15">
                    <label for="id_number" class="control-label"><?php echo app_lang('id_number'); ?> *</label>
                    <input type="text" name="id_number" id="id_number_page" class="form-control" placeholder="<?php echo app_lang('id_number'); ?>" data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>" />
                </div>
            </div>

            <!-- Contact & Organization -->
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

            <!-- Entry Gate & Officer to Visit -->
            <div class="row">
                <div class="col-md-6 form-group mb15">
                    <label for="gate_name" class="control-label"><?php echo app_lang('gate_name'); ?> *</label>
                    <?php echo form_dropdown("gate_name", $gates_dropdown, array("Main Gate - Lugogo"), "class='form-control select2' id='gate_name_page'"); ?>
                </div>
                <div class="col-md-6 form-group mb15">
                    <label for="to_user_id" class="control-label"><?php echo app_lang('person_to_visit'); ?> *</label>
                    <?php echo form_dropdown("to_user_id", $team_members_dropdown, array(), "class='form-control select2' id='to_user_id_page' data-rule-required='true' data-msg-required='" . app_lang('field_required') . "'"); ?>
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
        $("#visitor-logbook-page-form").appForm({
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 5000});
                window.location.href = "<?php echo get_uri('visitor_logbook'); ?>";
            }
        });

        $(".select2").select2();

        $(".nationality-toggle-page").change(function () {
            if ($(this).val() === "ugandan") {
                $("#ugandan-id-wrapper-page").removeClass("hide");
                $("#non-ugandan-id-wrapper-page").addClass("hide");
            } else {
                $("#ugandan-id-wrapper-page").addClass("hide");
                $("#non-ugandan-id-wrapper-page").removeClass("hide");
            }
        });
    });
</script>
