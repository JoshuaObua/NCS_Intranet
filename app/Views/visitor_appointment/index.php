<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo app_lang('appointments'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("visitor_appointment/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('create_an_appointment'), array("class" => "btn btn-default", "title" => app_lang('create_an_appointment'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="visitor-appointments-table" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#visitor-appointments-table").appTable({
            source: '<?php echo_uri("visitor_appointment/list_data") ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "<?php echo app_lang('visitor_appointment'); ?>"},
                {title: "<?php echo app_lang('phone'); ?>"},
                {title: "<?php echo app_lang('organization'); ?>"},
                {title: "<?php echo app_lang('person_to_visit'); ?>"},
                {title: "<?php echo app_lang('appointment_date'); ?>"},
                {title: "<?php echo app_lang('status'); ?>", "class": "text-center w100"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w125"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>
