<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Statutory Financial Statements</div>
                <h3 class="fw-bold text-primary mb-0">4 Returns</h3>
                <small class="text-muted">Auditor General Prepared</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">IPSAS 17 Property Valuation</div>
                <h3 class="fw-bold text-success mb-0">UGX 32.18B</h3>
                <small class="text-success">Fixed Asset Disclosure</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">PFMA 2015 Compliance</div>
                <h3 class="fw-bold text-info mb-0">100%</h3>
                <small class="text-muted">Ministry of Finance Compliant</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Audit Trail Integrity</div>
                <h3 class="fw-bold text-dark mb-0">Immutable</h3>
                <small class="text-success">Tamper-Proof Financial Logs</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="bar-chart-2" class="icon-16 mr5"></i> Financial Reports & Statutory Statements (PFMA 2015 / Treasury Instructions)</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Statements</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accounting-reports-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#accounting-reports-table").appTable({
            source: '<?php echo get_uri("accounting/reports_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Report Code", "class": "w120"},
                {title: "Statutory Financial Return Title & Scope"},
                {title: "Department", "class": "w180"},
                {title: "Accounting Standard", "class": "w180"},
                {title: "Cycle", "class": "w110"},
                {title: "Audit Status", "class": "w150 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w130"}
            ]
        });
    });
</script>
