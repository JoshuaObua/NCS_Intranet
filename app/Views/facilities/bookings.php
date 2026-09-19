<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Confirmed Venue Bookings</div>
                <h3 class="fw-bold text-primary mb-0">14</h3>
                <small class="text-muted">Active Pipeline</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Bookings Revenue</div>
                <h3 class="fw-bold text-success mb-0">UGX 95.0M</h3>
                <small class="text-success">Invoiced & Collected</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Caution Security Deposits</div>
                <h3 class="fw-bold text-info mb-0">UGX 17.0M</h3>
                <small class="text-muted">Escrowed Deposits</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">AGS-T Technical Clearance</div>
                <h3 class="fw-bold text-dark mb-0">100%</h3>
                <small class="text-success">Conflict Checked</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="calendar" class="icon-16 mr5"></i> Venue Bookings & Multi-Facility Interactive Scheduler</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("facilities/booking_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Add Venue Booking", array("class" => "btn btn-primary", "title" => "Create Venue Booking Application")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="facility-bookings-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#facility-bookings-table").appTable({
            source: '<?php echo get_uri("facilities/bookings_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Ref Code", "class": "w120"},
                {title: "Facility / Venue"},
                {title: "Event Title & Client Org"},
                {title: "Booking Schedule", "class": "w180"},
                {title: "Tariff Category", "class": "w160"},
                {title: "Total Fee (UGX)", "class": "w140 text-right"},
                {title: "Caution Deposit", "class": "w140 text-right"},
                {title: "Payment Status", "class": "w130 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
