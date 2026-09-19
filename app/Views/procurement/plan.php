<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="calendar" class="icon-16 mr5"></i> Annual Procurement Plan (APP) Tracking & Performance</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("procurement/plan_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Add APP Record", array("class" => "btn btn-primary", "title" => "Add Annual Procurement Plan Item")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="procurement-plans-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#procurement-plans-table").appTable({
            source: '<?php echo get_uri("procurement/plan_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Financial Year", "class": "w120"},
                {title: "Procurement Ref No", "class": "w150"},
                {title: "Subject of Procurement"},
                {title: "Type", "class": "w120"},
                {title: "Method", "class": "w130"},
                {title: "Est. Cost", "class": "w120 text-right"},
                {title: "User Department", "class": "w140"},
                {title: "Target Date", "class": "w120"},
                {title: "Status", "class": "w100 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
