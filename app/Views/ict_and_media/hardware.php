<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="cpu" class="icon-16 mr5"></i> IT & Media Equipment & Hardware Register</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("ict_and_media/hardware_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Register Equipment", array("class" => "btn btn-primary", "title" => "Register IT / Media Equipment")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="ict-hardware-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ict-hardware-table").appTable({
            source: '<?php echo get_uri("ict_and_media/hardware_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Item Code", "class": "w140"},
                {title: "Equipment Title & Specs"},
                {title: "Category", "class": "w180"},
                {title: "Cost (UGX)", "class": "w130 text-right"},
                {title: "Condition", "class": "w110 text-center"},
                {title: "Assigned Vault / Venue", "class": "w180"},
                {title: "Status", "class": "w110 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
