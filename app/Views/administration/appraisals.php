<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Fixed Asset Register Valuation</div>
                <h3 class="fw-bold text-primary mb-0">UGX 31.02B</h3>
                <small class="text-muted">Current Appraised Net Value</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Land & Property Valuation</div>
                <h3 class="fw-bold text-success mb-0">UGX 23.20B</h3>
                <small class="text-success">Lugogo & Regional Hubs</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Titled Land Parcels</div>
                <h3 class="fw-bold text-info mb-0">100%</h3>
                <small class="text-muted">NCS Registered Titles</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Insurance Coverage</div>
                <h3 class="fw-bold text-dark mb-0">94.5%</h3>
                <small class="text-muted">Under NIC Policy</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="award" class="icon-16 mr5"></i> Master Executive Asset Valuation & Fixed Asset Appraisal Register</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Valuation</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="admin-appraisals-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#admin-appraisals-table").appTable({
            source: '<?php echo get_uri("administration/appraisals_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Asset Tag", "class": "w120"},
                {title: "Asset Description & Location"},
                {title: "Asset Class", "class": "w140"},
                {title: "Historical Cost", "class": "w140 text-right"},
                {title: "Current Valuation", "class": "w140 text-right"},
                {title: "Net Book Value", "class": "w140 text-right"},
                {title: "Condition", "class": "w100 text-center"},
                {title: "Land Title Status", "class": "w160 text-center"},
                {title: "Insurance Status", "class": "w150 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    });
</script>
