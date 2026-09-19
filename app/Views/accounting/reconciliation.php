<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">NTR Bank Collections</div>
                <h3 class="fw-bold text-primary mb-0">UGX 342.5M</h3>
                <small class="text-muted">Stanbic NTR Account</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Treasury Subvention Balance</div>
                <h3 class="fw-bold text-success mb-0">UGX 1.85B</h3>
                <small class="text-success">Bank of Uganda TSA</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Statement Variance</div>
                <h3 class="fw-bold text-info mb-0">UGX 0.00</h3>
                <small class="text-success">100% Reconciled</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Bank Statement Cycle</div>
                <h3 class="fw-bold text-dark mb-0">Aug 2026</h3>
                <small class="text-muted">Cleared & Audited</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="shield" class="icon-16 mr5"></i> Non-Tax Revenue (NTR) Collection & Bank Reconciliation Ledger</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("accounting/reconciliation_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Post Bank Reconciliation", array("class" => "btn btn-primary", "title" => "Create Bank Statement Reconciliation Log")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accounting-reconciliation-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#accounting-reconciliation-table").appTable({
            source: '<?php echo get_uri("accounting/reconciliation_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Ref Code", "class": "w140"},
                {title: "Bank Account Title & Number"},
                {title: "Statement Date", "class": "w120"},
                {title: "System Balance (UGX)", "class": "w160 text-right"},
                {title: "Bank Balance (UGX)", "class": "w160 text-right"},
                {title: "Variance (UGX)", "class": "w140 text-right"},
                {title: "Reconciled By", "class": "w160"},
                {title: "Status", "class": "w140 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
