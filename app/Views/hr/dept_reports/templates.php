<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="settings" class="icon-16 mr5"></i> <?php echo app_lang("templates"); ?></h4>
            <div class="title-button-group">
                <?php echo anchor(get_uri("hr_dept_reports"), "<i data-feather='arrow-left' class='icon-16'></i> " . app_lang("back"), array("class" => "btn btn-default")); ?>
                <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("add"), array("class" => "btn btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("hr_dept_reports/template_modal_form"), "data-title" => app_lang("add_template"))); ?>
            </div>
        </div>
        <div class="card-body">
            <table id="templates-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo app_lang("title"); ?></th>
                        <th><?php echo app_lang("hr_department"); ?></th>
                        <th><?php echo app_lang("frequency"); ?></th>
                        <th><?php echo app_lang("status"); ?></th>
                        <th><?php echo app_lang("created_by"); ?></th>
                        <th><?php echo app_lang("date"); ?></th>
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
    $("#templates-table").appTable({
        source: "<?php echo_uri('hr_dept_reports/templates_list_data'); ?>",
        order: [[0, 'asc']],
        columns: [
            {title: "<?php echo app_lang('title'); ?>"},
            {title: "<?php echo app_lang('hr_department'); ?>"},
            {title: "<?php echo app_lang('frequency'); ?>"},
            {title: "<?php echo app_lang('status'); ?>"},
            {title: "<?php echo app_lang('created_by'); ?>"},
            {title: "<?php echo app_lang('date'); ?>"},
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
        ]
    });

    $(document).on("click", ".btn-template-edit", function () {
        openModal(get_uri("hr_dept_reports/template_modal_form"), {id: $(this).data("id")}, "<?php echo app_lang('edit'); ?>");
    });
    $(document).on("click", ".btn-template-delete", function () {
        deleteData(get_uri("hr_dept_reports/delete_template"), $(this).data("id"), function () { $("#templates-table").appTable({reload: true}); });
    });
});
</script>
