<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Executive Dossiers</div>
                <h3 class="fw-bold text-primary mb-0">5</h3>
                <small class="text-muted">Master Cross-Dept Reports</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Auditor General Compliance</div>
                <h3 class="fw-bold text-success mb-0">100%</h3>
                <small class="text-success">Statutory Disclosure Ready</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Fixed Asset Audit Return</div>
                <h3 class="fw-bold text-info mb-0">UGX 31.02B</h3>
                <small class="text-muted">Signed Accounting Officer</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Federations Subvention Return</div>
                <h3 class="fw-bold text-dark mb-0">UGX 29.80B</h3>
                <small class="text-muted">Grant Disbursement Return</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="bar-chart-2" class="icon-16 mr5"></i> Cross-Departmental Master Executive Reports</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Reports</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="admin-master-reports-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#admin-master-reports-table").appTable({
            source: '<?php echo get_uri("administration/master_reports_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Report Code", "class": "w120"},
                {title: "Report Title & Executive Scope"},
                {title: "Primary Department", "class": "w180"},
                {title: "Category", "class": "w180"},
                {title: "Frequency", "class": "w110"},
                {title: "Status", "class": "w160 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w130"}
            ]
        });
    });
</script>
