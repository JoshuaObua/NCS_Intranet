<?php echo form_open(get_uri("hr/save_profile"), array("id" => "hr-profile-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="user_id" value="<?php echo $model_info->user_id; ?>" />
    <div class="container-fluid">

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="employment_terms"><?php echo app_lang('employment_terms'); ?></label>
                <div class="col-md-4">
                    <?php echo form_dropdown("employment_terms", $employment_terms_dropdown, $model_info->employment_terms, "id='employment_terms' class='select2'"); ?>
                </div>
                <label class="col-md-2" for="gender"><?php echo app_lang('gender'); ?></label>
                <div class="col-md-3">
                    <?php echo form_dropdown("gender", $gender_dropdown, $model_info->gender, "id='gender' class='select2'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="marital_status"><?php echo app_lang('marital_status'); ?></label>
                <div class="col-md-4">
                    <?php echo form_dropdown("marital_status", $marital_status_dropdown, $model_info->marital_status, "id='marital_status' class='select2'"); ?>
                </div>
                <label class="col-md-2" for="national_id"><?php echo app_lang('national_id'); ?></label>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "national_id", "name" => "national_id", "value" => $model_info->national_id, "class" => "form-control")); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="supervisor_id"><?php echo app_lang('supervisor'); ?></label>
                <div class="col-md-9">
                    <?php echo form_dropdown("supervisor_id", $supervisor_dropdown, $model_info->supervisor_id, "id='supervisor_id' class='select2'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="salary_scale"><?php echo app_lang('salary_scale'); ?></label>
                <div class="col-md-4">
                    <?php echo form_input(array("id" => "salary_scale", "name" => "salary_scale", "value" => $model_info->salary_scale, "class" => "form-control")); ?>
                </div>
                <label class="col-md-2" for="date_of_birth"><?php echo app_lang('date_of_birth'); ?></label>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "date_of_birth", "name" => "date_of_birth", "value" => $model_info->date_of_birth, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="contract_start_date"><?php echo app_lang('contract_start'); ?></label>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "contract_start_date", "name" => "contract_start_date", "value" => $model_info->contract_start_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
                <label class="col-md-3" for="contract_end_date"><?php echo app_lang('contract_end'); ?></label>
                <div class="col-md-3">
                    <?php echo form_input(array("id" => "contract_end_date", "name" => "contract_end_date", "value" => $model_info->contract_end_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-3" for="notes"><?php echo app_lang('notes'); ?></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "notes", "name" => "notes", "value" => $model_info->notes, "class" => "form-control", "rows" => 3)); ?>
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
    $("#hr-profile-form").appForm({
        onSuccess: function () {
            $("#hr-table").appTable({reload: true});
        }
    });
    $(".datepicker").datepicker();
});
</script>
