<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="grid" class="icon-16 mr5"></i> <?php echo app_lang("departments"); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("departments/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang("create_department"), array("class" => "btn btn-primary", "title" => app_lang("create_department"))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="departments-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo app_lang("title"); ?></th>
                        <th><?php echo app_lang("description"); ?></th>
                        <th><?php echo app_lang("department_head"); ?></th>
                        <th><?php echo app_lang("roles"); ?></th>
                        <th class="text-center option w100"><?php echo app_lang("action"); ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#departments-table").appTable({
            source: '<?php echo_uri("departments/list_data"); ?>',
            columns: [
                {title: "<?php echo app_lang('title'); ?>"},
                {title: "<?php echo app_lang('description'); ?>"},
                {title: "<?php echo app_lang('department_head'); ?>"},
                {title: "<?php echo app_lang('roles'); ?>"},
                {title: "<i data-feather='menu' class='icon-16'></i>", class: "text-center option w100"}
            ]
        });
    });
</script>
