<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Fixed Asset Register Portfolio</div>
                <h3 class="fw-bold text-primary mb-0">UGX 32.18B</h3>
                <small class="text-muted">297 Appraised Assets</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Land & Property Valuation</div>
                <h3 class="fw-bold text-success mb-0">UGX 23.20B</h3>
                <small class="text-success">Lugogo, Hoima & Kapchorwa</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">IPSAS 17 Depreciation Standard</div>
                <h3 class="fw-bold text-info mb-0">Straight-Line</h3>
                <small class="text-muted">Automated Monthly Postings</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Baseline Excel Sync</div>
                <h3 class="fw-bold text-dark mb-0">100% Parity</h3>
                <small class="text-success">11 Excel Worksheets</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="hard-drive" class="icon-16 mr5"></i> Fixed Assets Register & IPSAS 17 Depreciation Ledger</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Run Depreciation</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="accounting-assets-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#accounting-assets-table").appTable({
            source: '<?php echo get_uri("accounting/assets_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Asset Tag", "class": "w120"},
                {title: "Asset Description & Location"},
                {title: "Asset Category", "class": "w150"},
                {title: "Historical Cost (UGX)", "class": "w150 text-right"},
                {title: "Current Valuation", "class": "w150 text-right"},
                {title: "Accumulated Depr", "class": "w140 text-right"},
                {title: "Net Book Value", "class": "w150 text-right"},
                {title: "Condition", "class": "w110 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    });
</script>
