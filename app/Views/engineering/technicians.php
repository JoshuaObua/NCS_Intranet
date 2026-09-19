<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="users" class="icon-16 mr5"></i> Engineering Technician Field Duty & Roster</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("engineering/technician_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Add Technician", array("class" => "btn btn-primary", "title" => "Add Technician Field Roster")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="technicians-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#technicians-table").appTable({
            source: '<?php echo get_uri("engineering/technicians_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Technician Name"},
                {title: "Trade / Discipline", "class": "w150"},
                {title: "Phone Contact", "class": "w140"},
                {title: "Certification Level", "class": "w160"},
                {title: "Stationed Facility", "class": "w160"},
                {title: "Duty Status", "class": "w120 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
