<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Approved Vote Budget</div>
                <h3 class="fw-bold text-primary mb-0">UGX 28.81B</h3>
                <small class="text-muted">Total Approved Budget</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Committed Sum</div>
                <h3 class="fw-bold text-success mb-0">UGX 21.61B</h3>
                <small class="text-success">75.0% Committed</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Uncommitted Balance</div>
                <h3 class="fw-bold text-info mb-0">UGX 7.20B</h3>
                <small class="text-muted">Available Q4 Buffer</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Budget Overspend Risk</div>
                <h3 class="fw-bold text-dark mb-0">0%</h3>
                <small class="text-success">PFMA 2015 Compliant</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="pie-chart" class="icon-16 mr5"></i> Government Budget Execution & Vote-Head Performance Command</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Execution</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accounting-budget-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#accounting-budget-table").appTable({
            source: '<?php echo get_uri("accounting/budget_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Vote Code", "class": "w120"},
                {title: "Vote-Head Name & Statutory Function"},
                {title: "Approved Budget (UGX)", "class": "w180 text-right"},
                {title: "Committed Sum (UGX)", "class": "w180 text-right"},
                {title: "Uncommitted Balance (UGX)", "class": "w180 text-right"},
                {title: "Execution Rate", "class": "w150 text-center"}
            ]
        });
    });
</script>
