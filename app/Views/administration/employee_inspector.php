<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Staff Establishment</div>
                <h3 class="fw-bold text-primary mb-0">128</h3>
                <small class="text-muted">Approved Public Service Roster</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Active Executive Staff</div>
                <h3 class="fw-bold text-success mb-0">124</h3>
                <small class="text-success">Active Duty Status</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Departments Mapped</div>
                <h3 class="fw-bold text-info mb-0">13</h3>
                <small class="text-muted">Strict Bureaucratic Hierarchy</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Profile Verification</div>
                <h3 class="fw-bold text-dark mb-0">100%</h3>
                <small class="text-muted">360° Inspector Ready</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="user" class="icon-16 mr5"></i> Employee 360° Profile & Departmental Rank Inspector</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Staff Roster</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="admin-employee-inspector-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#admin-employee-inspector-table").appTable({
            source: '<?php echo get_uri("administration/employee_inspector_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Employee Name"},
                {title: "Job Title", "class": "w160"},
                {title: "Department", "class": "w160"},
                {title: "Assigned Role & Bureaucratic Rank", "class": "w220"},
                {title: "Email Address", "class": "w180"},
                {title: "Phone", "class": "w130"},
                {title: "Status", "class": "w110 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w130"}
            ]
        });
    });
</script>
