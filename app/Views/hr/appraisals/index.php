<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="award" class="icon-16 mr5"></i> <?php echo app_lang("hr_appraisals"); ?></h4>
            <div class="title-button-group">
                <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "hr_appraisals") && get_array_value($login_user->permissions, "hr_appraisals") !== "read_only") { ?>
                    <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("add"), array("class" => "btn btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("hr_appraisals/modal_form"), "data-title" => app_lang("hr_appraisals"))); ?>
                <?php } ?>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb15">
                <div class="col-md-3">
                    <input type="text" id="appraisal-search" class="form-control" placeholder="<?php echo app_lang('search'); ?>..." />
                </div>
                <div class="col-md-2">
                    <?php echo form_dropdown("status_filter", $status_dropdown, "", "id='appraisal-status-filter' class='select2'"); ?>
                </div>
                <div class="col-md-2">
                    <select id="appraisal-year-filter" class="form-control select2">
                        <option value=""><?php echo "-- " . app_lang("all") . " --"; ?></option>
                        <?php foreach ($years as $y) { echo "<option value='{$y}'>{$y}</option>"; } ?>
                    </select>
                </div>
            </div>

            <table id="appraisals-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo app_lang("employee"); ?></th>
                        <th><?php echo app_lang("job_title"); ?></th>
                        <th><?php echo app_lang("period"); ?></th>
                        <th><?php echo app_lang("year"); ?></th>
                        <th><?php echo app_lang("supervisor"); ?></th>
                        <th><?php echo app_lang("status"); ?></th>
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
    $("#appraisals-table").appTable({
        source: "<?php echo_uri('hr_appraisals/list_data'); ?>",
        order: [[3, 'desc']],
        requestData: function () {
            return {
                search: $("#appraisal-search").val(),
                status: $("#appraisal-status-filter").val(),
                period_year: $("#appraisal-year-filter").val()
            };
        },
        columns: [
            {title: "<?php echo app_lang('employee'); ?>"},
            {title: "<?php echo app_lang('job_title'); ?>"},
            {title: "<?php echo app_lang('period'); ?>"},
            {title: "<?php echo app_lang('year'); ?>"},
            {title: "<?php echo app_lang('supervisor'); ?>"},
            {title: "<?php echo app_lang('status'); ?>"},
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
        ]
    });

    $("#appraisal-search").on("keyup", function () { $("#appraisals-table").appTable({reload: true}); });
    $("#appraisal-status-filter, #appraisal-year-filter").on("change", function () { $("#appraisals-table").appTable({reload: true}); });

    $(document).on("click", ".btn-appraisal-view", function () {
        window.location.href = get_uri("hr_appraisals/view/" + $(this).data("id"));
    });
    $(document).on("click", ".btn-appraisal-edit", function () {
        openModal(get_uri("hr_appraisals/modal_form"), {id: $(this).data("id")}, "<?php echo app_lang('edit'); ?>");
    });
    $(document).on("click", ".btn-appraisal-delete", function () {
        deleteData(get_uri("hr_appraisals/delete"), $(this).data("id"), function () { $("#appraisals-table").appTable({reload: true}); });
    });
});
</script>
