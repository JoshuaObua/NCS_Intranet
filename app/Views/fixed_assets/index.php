<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Fixed Assets Register</div>
                <h3 class="fw-bold text-primary mb-0">297 Assets</h3>
                <small class="text-muted">11 Excel Worksheet Classes</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Initial Cost Portfolio (FB_COST)</div>
                <h3 class="fw-bold text-dark mb-0">UGX 32.18B</h3>
                <small class="text-muted">Baseline Acquisition Cost</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Revalued Portfolio (Adjusted Cost)</div>
                <h3 class="fw-bold text-success mb-0">UGX 32.18B</h3>
                <small class="text-success">Current Adjusted Valuation</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Net Book Value (NBV)</div>
                <h3 class="fw-bold text-info mb-0">UGX 32.18B</h3>
                <small class="text-success">IPSAS 17 Compliant Balance</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="hard-drive" class="icon-16 mr5"></i> Master Fixed Assets Register & Portfolio Ledger</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("fixed_assets/modal_asset_form"), "<i data-feather='plus-circle' class='icon-16'></i> Add New Asset", array("class" => "btn btn-primary", "title" => "Add Fixed Asset")); ?>
                <?php echo modal_anchor(get_uri("fixed_assets/modal_import_form"), "<i data-feather='upload' class='icon-16'></i> Bulk Import Excel", array("class" => "btn btn-outline-primary", "title" => "Bulk Excel Import")); ?>
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="fixed-assets-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#fixed-assets-table").appTable({
            source: '<?php echo get_uri("fixed_assets/assets_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Asset Ref", "class": "w120"},
                {title: "Tag Number", "class": "w120"},
                {title: "Asset Description & Category"},
                {title: "Subcategory", "class": "w150"},
                {title: "FB Cost (UGX)", "class": "w140 text-right"},
                {title: "Adjusted Cost", "class": "w140 text-right"},
                {title: "Net Book Value", "class": "w140 text-right"},
                {title: "In Service", "class": "w110"},
                {title: "Status", "class": "w100 text-center"},
                {title: "Audit Ver", "class": "w110 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    });
</script>
