<div class="card border mt15">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><i data-feather="user-check" class="icon-14 mr5"></i> <?php echo app_lang("hr_profiles"); ?></strong>
        <?php if ($can_edit) { ?>
            <?php echo js_anchor("<i data-feather='edit' class='icon-14'></i> " . app_lang("edit"), array("class" => "btn btn-xs btn-default", "data-act" => "ajax-modal", "data-action-url" => get_uri("hr/profile_modal_form") . "?user_id=" . $profile->user_id, "data-title" => app_lang("hr_profiles"))); ?>
        <?php } ?>
    </div>
    <div class="card-body">
        <?php if (!$profile->id) { ?>
            <p class="text-muted"><?php echo app_lang("no_records_found"); ?></p>
        <?php } else { ?>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th><?php echo app_lang("employment_terms"); ?></th><td><?php echo $profile->employment_terms ? app_lang("hr_" . $profile->employment_terms) : "-"; ?></td></tr>
                    <tr><th><?php echo app_lang("salary_scale"); ?></th><td><?php echo $profile->salary_scale ?: "-"; ?></td></tr>
                    <tr><th><?php echo app_lang("supervisor"); ?></th><td><?php echo $profile->supervisor_name ?: "-"; ?></td></tr>
                    <tr><th><?php echo app_lang("contract_start"); ?></th><td><?php echo $profile->contract_start_date ? format_to_date($profile->contract_start_date) : "-"; ?></td></tr>
                    <tr><th><?php echo app_lang("contract_end"); ?></th><td><?php echo $profile->contract_end_date ? format_to_date($profile->contract_end_date) : "-"; ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-bordered">
                    <tr><th><?php echo app_lang("gender"); ?></th><td><?php echo $profile->gender ?: "-"; ?></td></tr>
                    <tr><th><?php echo app_lang("marital_status"); ?></th><td><?php echo $profile->marital_status ?: "-"; ?></td></tr>
                    <tr><th><?php echo app_lang("date_of_birth"); ?></th><td><?php echo $profile->date_of_birth ? format_to_date($profile->date_of_birth) : "-"; ?></td></tr>
                    <tr><th><?php echo app_lang("national_id"); ?></th><td><?php echo $profile->national_id ?: "-"; ?></td></tr>
                    <tr><th><?php echo app_lang("notes"); ?></th><td><?php echo $profile->notes ? nl2br(htmlspecialchars($profile->notes)) : "-"; ?></td></tr>
                </table>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
