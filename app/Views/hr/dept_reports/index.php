<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="file-text" class="icon-16 mr5"></i> <?php echo app_lang("hr_dept_reports"); ?></h4>
            <div class="title-button-group">
                <?php if ($login_user->is_admin) { ?>
                    <?php echo anchor(get_uri("hr_dept_reports/templates"), "<i data-feather='settings' class='icon-16'></i> " . app_lang("templates"), array("class" => "btn btn-default")); ?>
                <?php } ?>
                <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("submit_report"), array("class" => "btn btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("hr_dept_reports/modal_form"), "data-title" => app_lang("submit_report"))); ?>
            </div>
        </div>

        <?php if (!empty($dept_summary)) { ?>
        <div class="row p15 pb0">
            <?php
            $by_dept = [];
            foreach ($dept_summary as $s) { $by_dept[$s->department][$s->status] = $s->total; }
            foreach ($by_dept as $dept => $statuses) {
                $total = array_sum($statuses);
            ?>
            <div class="col-md-3 col-sm-6 mb15">
                <div class="card border h-100">
                    <div class="card-body text-center">
                        <i data-feather="folder" class="icon-24 text-primary"></i>
                        <h6 class="mt5 mb2"><?php echo $dept; ?></h6>
                        <span class="badge bg-info"><?php echo $total; ?> <?php echo app_lang("reports"); ?></span>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>

        <div class="card-body">
            <div class="row mb15">
                <div class="col-md-3">
                    <input type="text" id="report-search" class="form-control" placeholder="<?php echo app_lang('search'); ?>..." />
                </div>
                <div class="col-md-3">
                    <?php echo form_dropdown("template_filter", $templates_dropdown, "", "id='report-template-filter' class='select2'"); ?>
                </div>
                <div class="col-md-2">
                    <?php echo form_dropdown("status_filter", $status_dropdown, "", "id='report-status-filter' class='select2'"); ?>
                </div>
            </div>

            <table id="reports-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo app_lang("template"); ?></th>
                        <th><?php echo app_lang("hr_department"); ?></th>
                        <th><?php echo app_lang("period"); ?></th>
                        <th><?php echo app_lang("frequency"); ?></th>
                        <th><?php echo app_lang("submitted_by"); ?></th>
                        <th><?php echo app_lang("date"); ?></th>
                        <th><?php echo app_lang("status"); ?></th>
                        <th class="text-center w150"><?php echo app_lang("action"); ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $("#reports-table").appTable({
        source: "<?php echo_uri('hr_dept_reports/list_data'); ?>",
        order: [[5, 'desc']],
        requestData: function () {
            return {
                search: $("#report-search").val(),
                template_id: $("#report-template-filter").val(),
                status: $("#report-status-filter").val()
            };
        },
        columns: [
            {title: "<?php echo app_lang('template'); ?>"},
            {title: "<?php echo app_lang('hr_department'); ?>"},
            {title: "<?php echo app_lang('period'); ?>"},
            {title: "<?php echo app_lang('frequency'); ?>"},
            {title: "<?php echo app_lang('submitted_by'); ?>"},
            {title: "<?php echo app_lang('date'); ?>"},
            {title: "<?php echo app_lang('status'); ?>"},
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w150"}
        ]
    });

    $("#report-search").on("keyup", function () { $("#reports-table").appTable({reload: true}); });
    $("#report-template-filter, #report-status-filter").on("change", function () { $("#reports-table").appTable({reload: true}); });

    $(document).on("click", ".btn-report-edit", function () {
        openModal(get_uri("hr_dept_reports/modal_form"), {id: $(this).data("id")}, "<?php echo app_lang('edit'); ?>");
    });
    $(document).on("click", ".btn-report-delete", function () {
        deleteData(get_uri("hr_dept_reports/delete"), $(this).data("id"), function () { $("#reports-table").appTable({reload: true}); });
    });
    $(document).on("click", ".btn-report-approve", function () {
        appSave(get_uri("hr_dept_reports/approve"), {id: $(this).data("id")}, function () { $("#reports-table").appTable({reload: true}); });
    });
    $(document).on("click", ".btn-report-return", function () {
        appSave(get_uri("hr_dept_reports/return_report"), {id: $(this).data("id")}, function () { $("#reports-table").appTable({reload: true}); });
    });
});
</script>
