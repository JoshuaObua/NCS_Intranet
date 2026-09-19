<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="send" class="icon-16 mr5"></i> <?php echo app_lang("hr_memos_outbox"); ?></h4>
            <div class="title-button-group">
                <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("compose"), array("class" => "btn btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("hr_memos/modal_form"), "data-title" => app_lang("compose"))); ?>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb15">
                <div class="col-md-3">
                    <input type="text" id="memo-search" class="form-control" placeholder="<?php echo app_lang('search'); ?>..." />
                </div>
                <div class="col-md-2">
                    <select id="memo-priority-filter" class="form-control select2">
                        <option value=""><?php echo "-- " . app_lang("all") . " --"; ?></option>
                        <option value="low"><?php echo app_lang("hr_low"); ?></option>
                        <option value="normal"><?php echo app_lang("hr_normal"); ?></option>
                        <option value="high"><?php echo app_lang("hr_high"); ?></option>
                        <option value="urgent"><?php echo app_lang("hr_urgent"); ?></option>
                    </select>
                </div>
            </div>

            <table id="memos-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo app_lang("memo_number"); ?></th>
                        <th><?php echo app_lang("subject"); ?></th>
                        <th><?php echo app_lang("from"); ?></th>
                        <th><?php echo app_lang("date"); ?></th>
                        <th><?php echo app_lang("recipients"); ?></th>
                        <th><?php echo app_lang("priority"); ?></th>
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
    $("#memos-table").appTable({
        source: "<?php echo_uri('hr_memos/list_data'); ?>",
        order: [[3, 'desc']],
        requestData: function () {
            return {
                search: $("#memo-search").val(),
                priority: $("#memo-priority-filter").val()
            };
        },
        columns: [
            {title: "<?php echo app_lang('memo_number'); ?>"},
            {title: "<?php echo app_lang('subject'); ?>"},
            {title: "<?php echo app_lang('from'); ?>"},
            {title: "<?php echo app_lang('date'); ?>"},
            {title: "<?php echo app_lang('recipients'); ?>"},
            {title: "<?php echo app_lang('priority'); ?>"},
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
        ]
    });

    $("#memo-search").on("keyup", function () { $("#memos-table").appTable({reload: true}); });
    $("#memo-priority-filter").on("change", function () { $("#memos-table").appTable({reload: true}); });

    $(document).on("click", ".btn-memo-edit", function () {
        var id = $(this).data("id");
        openModal(get_uri("hr_memos/modal_form"), {id: id}, "<?php echo app_lang('edit'); ?>");
    });

    $(document).on("click", ".btn-memo-delete", function () {
        var id = $(this).data("id");
        deleteData(get_uri("hr_memos/delete"), id, function () { $("#memos-table").appTable({reload: true}); });
    });
});
</script>
