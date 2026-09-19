<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Facility Operations Returns</div>
                <h3 class="fw-bold text-primary mb-0">5 Dossiers</h3>
                <small class="text-muted">Combined Operations Reports</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">MoFPED NTR Clearance</div>
                <h3 class="fw-bold text-success mb-0">100%</h3>
                <small class="text-success">URA Bank Audit Ready</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Capital Maintenance Expenses</div>
                <h3 class="fw-bold text-info mb-0">UGX 1.25B</h3>
                <small class="text-muted">Contractor Costs Audited</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Dual Technical/Admin Audit</div>
                <h3 class="fw-bold text-dark mb-0">Verified</h3>
                <small class="text-success">AGS-T & AGS-A Signed</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="bar-chart-2" class="icon-16 mr5"></i> Facility Reports: Projects, Contractors, Expenses & NTR Statements</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Statements</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="facility-reports-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#facility-reports-table").appTable({
            source: '<?php echo get_uri("facilities/facility_reports_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Report Code", "class": "w120"},
                {title: "Facility Report Title & Context Scope"},
                {title: "Target Facility / Venue", "class": "w200"},
                {title: "Audit Category", "class": "w180"},
                {title: "Reporting Cycle", "class": "w110"},
                {title: "Audit Status", "class": "w160 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w130"}
            ]
        });
    });
</script>
