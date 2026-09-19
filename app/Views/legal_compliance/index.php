<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Active Contracts</div>
                <h3 class="fw-bold text-primary mb-0">UGX 29.40B</h3>
                <small class="text-muted">3 Legal Vault Records</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Active MOU & SLAs</div>
                <h3 class="fw-bold text-success mb-0">2 Active MOUs</h3>
                <small class="text-success">51 Sports Federations Covered</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Upcoming Expirations</div>
                <h3 class="fw-bold text-warning mb-0">1 Warning</h3>
                <small class="text-muted">< 90 Days Notice Required</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Legal Vetting Status</div>
                <h3 class="fw-bold text-info mb-0">100% Vetted</h3>
                <small class="text-success">Solicitor General Compliant</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="file-text" class="icon-16 mr5"></i> Legal Contracts Vault & Procurement Agreements</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("legal_compliance/modal_contract_form"), "<i data-feather='plus-circle' class='icon-16'></i> Add Contract / Agreement", array("class" => "btn btn-primary", "title" => "Add New Legal Contract")); ?>
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="contracts-vault-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#contracts-vault-table").appTable({
            source: '<?php echo get_uri("legal_compliance/contracts_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Contract Ref", "class": "w120"},
                {title: "Contract Title & Parties"},
                {title: "Category", "class": "w140"},
                {title: "Contract Value", "class": "w140 text-right"},
                {title: "Effective Date", "class": "w110"},
                {title: "Expiry Date", "class": "w110"},
                {title: "Status", "class": "w120 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    });
</script>
