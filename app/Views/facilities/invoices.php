<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Commercial Invoices</div>
                <h3 class="fw-bold text-primary mb-0">UGX 95.0M</h3>
                <small class="text-success">URA PRN Billing</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">NTR Collections Paid</div>
                <h3 class="fw-bold text-success mb-0">UGX 78.0M</h3>
                <small class="text-success">Finance Verified</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Pending Payments</div>
                <h3 class="fw-bold text-warning mb-0">UGX 17.0M</h3>
                <small class="text-muted">Partially Paid / Outstanding</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">URA PRN Integration</div>
                <h3 class="fw-bold text-dark mb-0">Active</h3>
                <small class="text-success">MoFPED Compliant</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="file-text" class="icon-16 mr5"></i> Commercial Non-Tax Revenue (NTR) Billing & Invoices</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Billing</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="facility-invoices-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#facility-invoices-table").appTable({
            source: '<?php echo get_uri("facilities/invoices_list_data"); ?>',
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
