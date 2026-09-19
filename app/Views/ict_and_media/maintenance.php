<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="tool" class="icon-16 mr5"></i> Cross-Departmental Repair & Maintenance Requisitions</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("ict_and_media/maintenance_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Submit Repair Request", array("class" => "btn btn-primary", "title" => "Submit Hardware Repair Requisition")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="ict-maintenance-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ict-maintenance-table").appTable({
            source: '<?php echo get_uri("ict_and_media/maintenance_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Req No", "class": "w140"},
                {title: "Equipment Item"},
                {title: "Requester & Department", "class": "w200"},
                {title: "Fault Category", "class": "w160"},
                {title: "Service Route", "class": "w150"},
                {title: "Est. Cost (UGX)", "class": "w130 text-right"},
                {title: "Status", "class": "w110 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
