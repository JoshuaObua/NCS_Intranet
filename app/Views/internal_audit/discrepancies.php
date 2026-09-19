<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Discrepancies Logged</div>
                <h3 class="fw-bold text-primary mb-0">3 Issues</h3>
                <small class="text-muted">Asset, Vote-Head & Stock Exceptions</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Open Audit Exceptions</div>
                <h3 class="fw-bold text-warning mb-0">1 Open</h3>
                <small class="text-warning">Under Review with Accountant</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Resolved Discrepancies</div>
                <h3 class="fw-bold text-success mb-0">1 Resolved</h3>
                <small class="text-success">Management Remediation Verified</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Financial Exposure</div>
                <h3 class="fw-bold text-info mb-0">UGX 1.50M</h3>
                <small class="text-muted">Low Exposure Risk</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="alert-triangle" class="icon-16 mr5"></i> Audit Discrepancy & Exception Manager</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("internal_audit/modal_discrepancy_form"), "<i data-feather='plus-circle' class='icon-16'></i> Raise Audit Discrepancy", array("class" => "btn btn-primary", "title" => "Raise Audit Discrepancy")); ?>
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="discrepancies-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#discrepancies-table").appTable({
            source: '<?php echo get_uri("internal_audit/discrepancies_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Code", "class": "w130"},
                {title: "Entity", "class": "w120"},
                {title: "Ref", "class": "w140"},
                {title: "Finding Title & Description"},
                {title: "Severity", "class": "w100 text-center"},
                {title: "Impact (UGX)", "class": "w130 text-right"},
                {title: "Status", "class": "w120 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    });
</script>
