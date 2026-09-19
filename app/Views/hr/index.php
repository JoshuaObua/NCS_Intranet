<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="user-check" class="icon-16 mr5"></i> <?php echo app_lang("hr_profiles"); ?></h4>
            <div class="title-button-group">
                <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "hr") !== "read_only") { ?>
                    <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("add"), array("class" => "btn btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("hr/profile_modal_form"), "data-title" => app_lang("hr_profiles"))); ?>
                <?php } ?>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb15">
                <div class="col-md-3">
                    <input type="text" id="hr-search" class="form-control" placeholder="<?php echo app_lang('search'); ?>..." />
                </div>
                <div class="col-md-3">
                    <?php echo form_dropdown("department_filter", $dept_dropdown, "", "id='dept-filter' class='select2'"); ?>
                </div>
                <div class="col-md-3">
                    <?php echo form_dropdown("terms_filter", $employment_terms_dropdown, "", "id='terms-filter' class='select2'"); ?>
                </div>
            </div>

            <table id="hr-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo app_lang("member"); ?></th>
                        <th><?php echo app_lang("job_title"); ?></th>
                        <th><?php echo app_lang("department"); ?></th>
                        <th><?php echo app_lang("employment_terms"); ?></th>
                        <th><?php echo app_lang("supervisor"); ?></th>
                        <th><?php echo app_lang("salary_scale"); ?></th>
                        <th><?php echo app_lang("contract_expiry"); ?></th>
                        <th class="text-center w100"><?php echo app_lang("action"); ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $("#hr-table").appTable({
        source: "<?php echo_uri('hr/list_data'); ?>",
        order: [[0, 'asc']],
        requestData: function () {
            return {
                search: $("#hr-search").val(),
                department: $("#dept-filter").val(),
                employment_terms: $("#terms-filter").val()
            };
        },
        columns: [
            {title: "<?php echo app_lang('member'); ?>"},
            {title: "<?php echo app_lang('job_title'); ?>"},
            {title: "<?php echo app_lang('department'); ?>"},
            {title: "<?php echo app_lang('employment_terms'); ?>"},
            {title: "<?php echo app_lang('supervisor'); ?>"},
            {title: "<?php echo app_lang('salary_scale'); ?>"},
            {title: "<?php echo app_lang('contract_expiry'); ?>"},
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
        ]
    });

    $("#hr-search").on("keyup", function () { $("#hr-table").appTable({reload: true}); });
    $("#dept-filter, #terms-filter").on("change", function () { $("#hr-table").appTable({reload: true}); });

    $(document).on("click", ".btn-hr-edit", function () {
        openModal(get_uri("hr/profile_modal_form"), {user_id: $(this).data("id")}, "<?php echo app_lang('edit'); ?>");
    });
});
</script>
