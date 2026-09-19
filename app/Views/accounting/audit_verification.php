<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Physical Barcode Spot-Checks</div>
                <h3 class="fw-bold text-primary mb-0">297 / 297</h3>
                <small class="text-success">100% Physical Verification</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Open Audit Discrepancies</div>
                <h3 class="fw-bold text-success mb-0">0 Discrepancies</h3>
                <small class="text-success">Clean Audit Trail</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Internal Audit Rating</div>
                <h3 class="fw-bold text-info mb-0">LOW RISK</h3>
                <small class="text-muted">High Financial Integrity</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Segregation of Duties (SoD)</div>
                <h3 class="fw-bold text-dark mb-0">Enforced</h3>
                <small class="text-success">Auditor Independent Access</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="eye" class="icon-16 mr5"></i> Internal Audit Verification, Spot-Checks & Discrepancy Manager</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Audit Log</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accounting-audit-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#accounting-audit-table").appTable({
            source: '<?php echo get_uri("accounting/audit_verification_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Audit Ref", "class": "w130"},
                {title: "Audit Scope & Verification Target"},
                {title: "Auditor Lead", "class": "w180"},
                {title: "Date", "class": "w110"},
                {title: "Compliance Status", "class": "w180 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w130"}
            ]
        });
    });
</script>
