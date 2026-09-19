<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Annual Government Subvention</div>
                <h3 class="fw-bold text-primary mb-0">UGX 25.0B</h3>
                <small class="text-muted">PFMA 2015 Approved Budget</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Fixed Asset Net Book Value</div>
                <h3 class="fw-bold text-success mb-0">UGX 32.18B</h3>
                <small class="text-success">297 Items Appraised</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Federation Subventions Disbursed</div>
                <h3 class="fw-bold text-info mb-0">UGX 4.74B</h3>
                <small class="text-info">50+ Federations YTD</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Vote Budget Commitment</div>
                <h3 class="fw-bold text-dark mb-0">75.0%</h3>
                <small class="text-muted">On Track Commitment</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="file-text" class="icon-16 mr5"></i> General Ledger & Journal Posting Command Workspace</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("accounting/journal_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Post Journal Entry", array("class" => "btn btn-primary", "title" => "Post Double-Entry Journal Voucher")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accounting-ledger-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#accounting-ledger-table").appTable({
            source: '<?php echo get_uri("accounting/ledger_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Voucher No", "class": "w120"},
                {title: "Posting Date", "class": "w110"},
                {title: "Vote-Head Account", "class": "w220"},
                {title: "Journal Description & Transaction Details"},
                {title: "Debit (UGX)", "class": "w150 text-right"},
                {title: "Credit (UGX)", "class": "w150 text-right"},
                {title: "Poster Officer", "class": "w160"},
                {title: "Status", "class": "w110 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
