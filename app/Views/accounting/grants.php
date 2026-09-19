<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Subvention Grants</div>
                <h3 class="fw-bold text-primary mb-0">UGX 18.00B</h3>
                <small class="text-muted">FY 2026/27 Subvention Budget</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Disbursed YTD</div>
                <h3 class="fw-bold text-success mb-0">UGX 4.74B</h3>
                <small class="text-success">Cleared Disbursements</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Verified Accountabilities</div>
                <h3 class="fw-bold text-info mb-0">85.0%</h3>
                <small class="text-muted">Audit Approved Returns</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Registered Federations</div>
                <h3 class="fw-bold text-dark mb-0">51 Federations</h3>
                <small class="text-muted">Sports Act 2023 Grant Pool</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="dollar-sign" class="icon-16 mr5"></i> National Sports Federations Subventions & Grant Disbursements</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("accounting/grant_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Issue Subvention Grant", array("class" => "btn btn-primary", "title" => "Disburse Federation Grant Voucher")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accounting-grants-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#accounting-grants-table").appTable({
            source: '<?php echo get_uri("accounting/grants_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Code", "class": "w90"},
                {title: "Federation Name & Purpose"},
                {title: "Quarter", "class": "w130"},
                {title: "Allocated Budget (UGX)", "class": "w160 text-right"},
                {title: "Disbursed YTD (UGX)", "class": "w160 text-right"},
                {title: "Disbursement Date", "class": "w130"},
                {title: "Accountability Status", "class": "w150 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
