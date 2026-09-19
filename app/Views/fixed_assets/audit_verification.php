<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Verification Rate</div>
                <h3 class="fw-bold text-success mb-0">33.3%</h3>
                <small class="text-success">99 Verified Spot-Checks</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Physical Barcode Tagging</div>
                <h3 class="fw-bold text-primary mb-0">100% Tagged</h3>
                <small class="text-primary">297 Physical Tags Attached</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Audit Discrepancies</div>
                <h3 class="fw-bold text-success mb-0">0 Open</h3>
                <small class="text-success">Clean Audit Trail</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Segregation of Duties</div>
                <h3 class="fw-bold text-dark mb-0">Enforced</h3>
                <small class="text-success">Auditor Independent Scanner</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="check-square" class="icon-16 mr5"></i> Internal Audit Physical Tag Scanning & Spot-Checks Manager</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Spot-Check Queue</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="audit-spotcheck-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#audit-spotcheck-table").appTable({
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
    });
</script>
