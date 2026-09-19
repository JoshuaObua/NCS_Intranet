<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Public Service HR Directives</div>
                <h3 class="fw-bold text-primary mb-0">100%</h3>
                <small class="text-success">Statutory Compliance</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Departmental Units</div>
                <h3 class="fw-bold text-success mb-0">13</h3>
                <small class="text-muted">Structured Ranks 1 to 5</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Appraisal Completion Rate</div>
                <h3 class="fw-bold text-info mb-0">98.2%</h3>
                <small class="text-muted">FY 2025/26 Cycle</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Disciplinary & Grievances</div>
                <h3 class="fw-bold text-dark mb-0">0 Pending</h3>
                <small class="text-success">Clean Record</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="shield" class="icon-16 mr5"></i> Administrative Governance & Departmental HR Roster</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Audit</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="admin-hr-governance-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#admin-hr-governance-table").appTable({
            source: '<?php echo get_uri("administration/hr_governance_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Department Name"},
                {title: "Headcount Baseline", "class": "w180"},
                {title: "Active Deployment", "class": "w160"},
                {title: "Governance Framework", "class": "w220"},
                {title: "Compliance Rate", "class": "w140 text-center"},
                {title: "Status", "class": "w140 text-center"}
            ]
        });
    });
</script>
