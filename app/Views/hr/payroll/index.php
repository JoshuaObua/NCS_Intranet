<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="dollar-sign" class="icon-16 mr5"></i> <?php echo app_lang("hr_payroll"); ?></h4>
            <div class="title-button-group">
                <?php if ($login_user->is_admin || get_array_value($login_user->permissions, "hr_payroll") !== "read_only") { ?>
                    <?php echo js_anchor("<i data-feather='plus' class='icon-16'></i> " . app_lang("add"), array("class" => "btn btn-primary", "data-act" => "ajax-modal", "data-action-url" => get_uri("hr_payroll/modal_form"), "data-title" => app_lang("add_payroll"))); ?>
                <?php } ?>
            </div>
        </div>

        <?php if (isset($current_period_summary) && $current_period_summary) { ?>
        <div class="row p15 pb0">
            <div class="col-md-3">
                <div class="card border-primary"><div class="card-body text-center">
                    <small><?php echo app_lang("total_gross"); ?></small>
                    <h5 class="text-primary"><?php echo to_currency($current_period_summary->total_gross); ?></h5>
                </div></div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning"><div class="card-body text-center">
                    <small><?php echo app_lang("total_paye"); ?></small>
                    <h5 class="text-warning"><?php echo to_currency($current_period_summary->total_paye); ?></h5>
                </div></div>
            </div>
            <div class="col-md-3">
                <div class="card border-info"><div class="card-body text-center">
                    <small><?php echo app_lang("total_nssf"); ?></small>
                    <h5 class="text-info"><?php echo to_currency($current_period_summary->total_nssf); ?></h5>
                </div></div>
            </div>
            <div class="col-md-3">
                <div class="card border-success"><div class="card-body text-center">
                    <small><?php echo app_lang("total_net"); ?></small>
                    <h5 class="text-success"><?php echo to_currency($current_period_summary->total_net); ?></h5>
                </div></div>
            </div>
        </div>
        <?php } ?>

        <div class="card-body">
            <div class="row mb15">
                <div class="col-md-3">
                    <input type="text" id="payroll-search" class="form-control" placeholder="<?php echo app_lang('search'); ?>..." />
                </div>
                <div class="col-md-2">
                    <?php echo form_dropdown("period_filter", $period_dropdown, get_setting("current_payroll_period"), "id='payroll-period-filter' class='select2'"); ?>
                </div>
                <div class="col-md-2">
                    <?php echo form_dropdown("status_filter", $status_dropdown, "", "id='payroll-status-filter' class='select2'"); ?>
                </div>
            </div>

            <table id="payroll-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?php echo app_lang("employee"); ?></th>
                        <th><?php echo app_lang("period"); ?></th>
                        <th><?php echo app_lang("basic_salary"); ?></th>
                        <th><?php echo app_lang("gross_pay"); ?></th>
                        <th><?php echo app_lang("paye"); ?></th>
                        <th><?php echo app_lang("nssf"); ?></th>
                        <th><?php echo app_lang("net_pay"); ?></th>
                        <th><?php echo app_lang("status"); ?></th>
                        <th class="text-center w120"><?php echo app_lang("action"); ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $("#payroll-table").appTable({
        source: "<?php echo_uri('hr_payroll/list_data'); ?>",
        order: [[1, 'desc']],
        requestData: function () {
            return {
                search: $("#payroll-search").val(),
                period: $("#payroll-period-filter").val(),
                status: $("#payroll-status-filter").val()
            };
        },
        columns: [
            {title: "<?php echo app_lang('employee'); ?>"},
            {title: "<?php echo app_lang('period'); ?>"},
            {title: "<?php echo app_lang('basic_salary'); ?>"},
            {title: "<?php echo app_lang('gross_pay'); ?>"},
            {title: "<?php echo app_lang('paye'); ?>"},
            {title: "<?php echo app_lang('nssf'); ?>"},
            {title: "<?php echo app_lang('net_pay'); ?>"},
            {title: "<?php echo app_lang('status'); ?>"},
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w120"}
        ]
    });

    $("#payroll-search").on("keyup", function () { $("#payroll-table").appTable({reload: true}); });
    $("#payroll-period-filter, #payroll-status-filter").on("change", function () { $("#payroll-table").appTable({reload: true}); });

    $(document).on("click", ".btn-payroll-edit", function () {
        openModal(get_uri("hr_payroll/modal_form"), {id: $(this).data("id")}, "<?php echo app_lang('edit'); ?>");
    });
    $(document).on("click", ".btn-payroll-delete", function () {
        deleteData(get_uri("hr_payroll/delete"), $(this).data("id"), function () { $("#payroll-table").appTable({reload: true}); });
    });
    $(document).on("click", ".btn-payroll-approve", function () {
        appSave(get_uri("hr_payroll/approve"), {id: $(this).data("id")}, function () { $("#payroll-table").appTable({reload: true}); });
    });
});
</script>
