<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="check-square" class="icon-16 mr5"></i> Facility Inspections & Event Readiness Audit Log</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("engineering/inspection_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Conduct New Inspection", array("class" => "btn btn-primary", "title" => "Conduct Facility / Stadium Safety Inspection")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="engineering-inspections-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#engineering-inspections-table").appTable({
            source: '<?php echo get_uri("engineering/inspections_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Inspection Code", "class": "w150"},
                {title: "Event / Facility Venue"},
                {title: "Inspection Date", "class": "w120"},
                {title: "Inspector", "class": "w150"},
                {title: "Civil Safety", "class": "w110 text-center"},
                {title: "Electrical Safety", "class": "w110 text-center"},
                {title: "Readiness Score", "class": "w120 text-center"},
                {title: "Status", "class": "w120 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
