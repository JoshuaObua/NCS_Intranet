<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Hostel Rooms</div>
                <h3 class="fw-bold text-primary mb-0">40 Rooms</h3>
                <small class="text-muted">166BLNG1 Residential Block</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Active Resident Athletes</div>
                <h3 class="fw-bold text-success mb-0">32 Athletes</h3>
                <small class="text-success">National Team Camps</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Federations Stationed</div>
                <h3 class="fw-bold text-info mb-0">3 Federations</h3>
                <small class="text-muted">UAF, UNF, UBF Squads</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Hostel Room Occupancy</div>
                <h3 class="fw-bold text-dark mb-0">80.0%</h3>
                <small class="text-success">Capacity Utilization</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="home" class="icon-16 mr5"></i> Lugogo Hostels Athlete Accommodation & Residence Hub</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("facilities/hostel_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Assign Athlete Room", array("class" => "btn btn-primary", "title" => "Assign Lugogo Hostel Room")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="hostel-occupancies-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#hostel-occupancies-table").appTable({
            source: '<?php echo get_uri("facilities/hostels_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Room No", "class": "w110"},
                {title: "Athlete Resident & ID/NIN"},
                {title: "Sports Federation", "class": "w180"},
                {title: "Gender", "class": "w90"},
                {title: "Camp Duration", "class": "w180"},
                {title: "Key Status", "class": "w120 text-center"},
                {title: "Camp Status", "class": "w130 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
