<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="tool" class="icon-16 mr5"></i> Work Orders & Preventive Maintenance Logbook</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("engineering/work_order_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Issue New Work Order", array("class" => "btn btn-primary", "title" => "Issue Engineering Work Order")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="engineering-work-orders-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#engineering-work-orders-table").appTable({
            source: '<?php echo get_uri("engineering/work_orders_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "WO Number", "class": "w150"},
                {title: "Title"},
                {title: "Category", "class": "w130"},
                {title: "Location / Venue", "class": "w160"},
                {title: "Priority", "class": "w110 text-center"},
                {title: "Est. Cost", "class": "w120 text-right"},
                {title: "Requester", "class": "w140"},
                {title: "Status", "class": "w120 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
