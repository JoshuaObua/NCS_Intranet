<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="hard-drive" class="icon-16 mr5"></i> Infrastructure & Sports Facility Asset Register</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("engineering/asset_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Register Asset", array("class" => "btn btn-primary", "title" => "Register Infrastructure Asset")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="engineering-assets-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#engineering-assets-table").appTable({
            source: '<?php echo get_uri("engineering/assets_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Asset Code", "class": "w150"},
                {title: "Asset Name"},
                {title: "Category", "class": "w140"},
                {title: "Facility Location", "class": "w160"},
                {title: "Condition", "class": "w110 text-center"},
                {title: "Value (UGX)", "class": "w130 text-right"},
                {title: "Last Inspected", "class": "w120"},
                {title: "Next Maint. Due", "class": "w120"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
