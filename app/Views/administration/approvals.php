<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Pending Vetting Queue</div>
                <h3 class="fw-bold text-primary mb-0">4</h3>
                <small class="text-warning">Accounting Officer Scope</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Requisition Value</div>
                <h3 class="fw-bold text-success mb-0">UGX 1.88B</h3>
                <small class="text-muted">Procurement & Capex</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">High Priority / Urgent</div>
                <h3 class="fw-bold text-danger mb-0">2</h3>
                <small class="text-danger">Requires Immediate Sign-off</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Vetted & Approved (YTD)</div>
                <h3 class="fw-bold text-dark mb-0">128</h3>
                <small class="text-success">Compliance Verified</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="check-square" class="icon-16 mr5"></i> Executive Approvals & Vetting Dashboard</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Queue</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="admin-approvals-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#admin-approvals-table").appTable({
            source: '<?php echo get_uri("administration/approvals_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Ref No", "class": "w140"},
                {title: "Module Type", "class": "w140"},
                {title: "Requisition Subject & Originator"},
                {title: "Amount (UGX)", "class": "w140 text-right"},
                {title: "Urgency", "class": "w110 text-center"},
                {title: "Target Approver", "class": "w150"},
                {title: "Status", "class": "w150 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w120"}
            ]
        });
    });
</script>
