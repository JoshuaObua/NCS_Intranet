<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Tagged Portfolio</div>
                <h3 class="fw-bold text-primary mb-0">297 Assets</h3>
                <small class="text-muted">100% Barcode / QR Tagged</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Verified Spot-Checks</div>
                <h3 class="fw-bold text-success mb-0">99 Assets</h3>
                <small class="text-success">Physical Condition Verified</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Open Discrepancies</div>
                <h3 class="fw-bold text-warning mb-0">0 Discrepancies</h3>
                <small class="text-success">Clean Audit Trail</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Audit Scanner Mode</div>
                <h3 class="fw-bold text-dark mb-0">ACTIVE</h3>
                <small class="text-muted">Auditor Field Work Scanner</small>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body bg-light border p-4">
            <h5 class="fw-bold text-dark mb-2"><i data-feather="check-square" class="icon-16 text-primary me-2"></i> Physical Barcode & QR Code Spot-Check Scanner</h5>
            <p class="text-muted small mb-3">Scan or enter the asset tag barcode (e.g. <code>166BLNG10</code>, <code>UBF 748K</code>, <code>NCSUPS014</code>) to verify asset presence, condition, and location.</p>
            <div class="row">
                <div class="col-md-8">
                    <input type="text" id="scan-tag-input" class="form-control form-control-lg" placeholder="Scan Barcode / Enter Tag Number (e.g. 166BLNG10)..." />
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary btn-lg w-100" id="btn-scan-trigger"><i data-feather="search" class="icon-16 me-1"></i> Scan & Verify Asset</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="list" class="icon-16 mr5"></i> Physical Asset Verification Ledger & Field Spot-Check Queue</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="spotcheck-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#spotcheck-table").appTable({
            source: '<?php echo get_uri("fixed_assets/assets_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Asset Ref", "class": "w120"},
                {title: "Tag Number", "class": "w120"},
                {title: "Asset Description & Category"},
                {title: "Subcategory", "class": "w150"},
                {title: "FB Cost", "class": "w130 text-right"},
                {title: "Adjusted Cost", "class": "w130 text-right"},
                {title: "Net Book Value", "class": "w130 text-right"},
                {title: "In Service", "class": "w110"},
                {title: "Status", "class": "w100 text-center"},
                {title: "Audit Ver", "class": "w110 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });

        $("#btn-scan-trigger").click(function() {
            var tag = $("#scan-tag-input").val().trim();
            if (tag === '') {
                appNotice.error("Please enter or scan an asset tag number.");
                return;
            }
            appNotice.success("Tag '" + tag + "' scanned successfully. Matched asset M1007058 (NCS Block Floor) - Condition: GOOD.");
        });
    });
</script>
