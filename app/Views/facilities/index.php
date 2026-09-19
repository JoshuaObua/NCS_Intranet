<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Monthly Arena NTR Revenue</div>
                <h3 class="fw-bold text-primary mb-0">UGX 142.5M</h3>
                <small class="text-success">Non-Tax Revenue Target</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Arena Occupancy Rate</div>
                <h3 class="fw-bold text-success mb-0">88.5%</h3>
                <small class="text-muted">This Month</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Lugogo Hostel Rooms</div>
                <h3 class="fw-bold text-info mb-0">32 / 40 Full</h3>
                <small class="text-info">National Team Camps</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Upcoming Venue Events</div>
                <h3 class="fw-bold text-dark mb-0">12 Fixtures</h3>
                <small class="text-muted">Next 7 Days</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="map-pin" class="icon-16 mr5"></i> Facilities Register & Venue Operations Command Hub</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("facilities/facility_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Register Facility", array("class" => "btn btn-primary", "title" => "Register Sporting Facility / Venue")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="facilities-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#facilities-table").appTable({
            source: '<?php echo get_uri("facilities/facilities_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Code", "class": "w110"},
                {title: "Facility / Venue Title & Description"},
                {title: "Category", "class": "w150"},
                {title: "Location Zone", "class": "w150"},
                {title: "Capacity", "class": "w110 text-right"},
                {title: "NTR Tariff Rate", "class": "w160 text-right"},
                {title: "Caution Deposit", "class": "w150 text-right"},
                {title: "Operational Status", "class": "w140 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
