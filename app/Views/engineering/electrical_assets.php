<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="zap" class="icon-16 mr5"></i> Electrical Machinery, Generators & Telemetry Register</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("engineering/log_generator_modal"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Register Electrical Equipment", array("class" => "btn btn-primary", "title" => "Register Electrical Machinery")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="electrical-assets-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#electrical-assets-table").appTable({
            source: '<?php echo get_uri("engineering/electrical_assets_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Equipment Code", "class": "w140"},
                {title: "Equipment & Capacity"},
                {title: "Location / Station", "class": "w160"},
                {title: "Fuel Reserve / Tank", "class": "w150"},
                {title: "Runtime Hours", "class": "w120"},
                {title: "ATS Auto-Start", "class": "w120 text-center"},
                {title: "Last Service", "class": "w120"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
