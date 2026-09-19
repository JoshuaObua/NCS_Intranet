<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Event Audits</div>
                <h3 class="fw-bold text-primary mb-0">18 Audits</h3>
                <small class="text-muted">Pre & Post Event</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Pre-Event Safety Passed</div>
                <h3 class="fw-bold text-success mb-0">100%</h3>
                <small class="text-success">Fire & Security Cleared</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Caution Deposits Refunded</div>
                <h3 class="fw-bold text-info mb-0">UGX 17.0M</h3>
                <small class="text-muted">No Structural Damage</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Damage Deductions YTD</div>
                <h3 class="fw-bold text-dark mb-0">UGX 0.00</h3>
                <small class="text-success">Clean Venue Record</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="check-square" class="icon-16 mr5"></i> Pre & Post Event Safety & Damage Inspections</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("facilities/inspection_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Log Event Inspection", array("class" => "btn btn-primary", "title" => "Log Pre/Post Event Safety Inspection")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="facility-inspections-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#facility-inspections-table").appTable({
            source: '<?php echo get_uri("facilities/inspections_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Booking Ref", "class": "w120"},
                {title: "Event Title & Facility"},
                {title: "Inspection Type", "class": "w160"},
                {title: "Inspector", "class": "w160"},
                {title: "Safety Clearance", "class": "w160 text-center"},
                {title: "Damage Deduction", "class": "w140 text-right"},
                {title: "Caution Refund Status", "class": "w160 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
