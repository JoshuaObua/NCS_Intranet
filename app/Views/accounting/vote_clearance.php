<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Pending PPDA Form 5s</div>
                <h3 class="fw-bold text-primary mb-0">3 Requisitions</h3>
                <small class="text-muted">Financial Vote Check Queue</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Vote Budget Cleared YTD</div>
                <h3 class="fw-bold text-success mb-0">UGX 91.5M</h3>
                <small class="text-success">Passed Clearance</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Budget Overspend Risk</div>
                <h3 class="fw-bold text-info mb-0">0%</h3>
                <small class="text-muted">Strict PFMA 2015 Controls</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Senior Accountant Clearance</div>
                <h3 class="fw-bold text-dark mb-0">Active</h3>
                <small class="text-success">Vote-Head Enforcement</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="check-square" class="icon-16 mr5"></i> PPDA Form 5 Financial Vote-Head Budget Clearance</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Queue</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accounting-vote-clearance-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#accounting-vote-clearance-table").appTable({
            source: '<?php echo get_uri("accounting/vote_clearance_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Requisition Ref", "class": "w140"},
                {title: "Department", "class": "w150"},
                {title: "Target Vote-Head Code & Title"},
                {title: "Requested Sum (UGX)", "class": "w150 text-right"},
                {title: "Available Budget (UGX)", "class": "w160 text-right"},
                {title: "Clearance Officer", "class": "w160"},
                {title: "Financial Clearance", "class": "w130 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w110"}
            ]
        });
    });
</script>
