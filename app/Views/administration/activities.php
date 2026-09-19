<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total System Events</div>
                <h3 class="fw-bold text-primary mb-0">1,480</h3>
                <small class="text-muted">Audit Logged (YTD)</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Executive Actions Today</div>
                <h3 class="fw-bold text-success mb-0">18</h3>
                <small class="text-success">Vettings & Approvals</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Security & IP Compliance</div>
                <h3 class="fw-bold text-info mb-0">100%</h3>
                <small class="text-muted">Internal Network Verified</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Immutable Vault</div>
                <h3 class="fw-bold text-dark mb-0">Active</h3>
                <small class="text-success">Tamper-Proof Logs</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="activity" class="icon-16 mr5"></i> Executive Administration Activity & Audit Trail</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Log</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="admin-activities-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#admin-activities-table").appTable({
            source: '<?php echo get_uri("administration/activities_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Timestamp", "class": "w160"},
                {title: "Executive User", "class": "w180"},
                {title: "Action Type", "class": "w180"},
                {title: "Detailed Event Description"},
                {title: "IP Address", "class": "w120 text-center"}
            ]
        });
    });
</script>
