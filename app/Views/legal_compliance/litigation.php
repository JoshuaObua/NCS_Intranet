<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Active Cases</div>
                <h3 class="fw-bold text-primary mb-0">3 High Court Cases</h3>
                <small class="text-muted">Civil, Land & Administrative Suits</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Exposure Risk</div>
                <h3 class="fw-bold text-danger mb-0">UGX 1.25B</h3>
                <small class="text-danger">Contingent Liability Provision</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">External Counsel Lead</div>
                <h3 class="fw-bold text-info mb-0">Attorney General</h3>
                <small class="text-muted">Solicitor General External Legal Team</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Next Hearing Date</div>
                <h3 class="fw-bold text-dark mb-0">2026-10-15</h3>
                <small class="text-warning">Civil Suit #142/2025 Hearing</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="briefcase" class="icon-16 mr5"></i> Litigation, Court Cases & Contingent Liabilities Management</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("legal_compliance/modal_litigation_form"), "<i data-feather='plus-circle' class='icon-16'></i> Add Court Case Record", array("class" => "btn btn-primary", "title" => "Add Litigation Case")); ?>
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="litigation-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#litigation-table").appTable({
            source: '<?php echo get_uri("legal_compliance/litigation_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Case Ref", "class": "w130"},
                {title: "Court / Forum", "class": "w160"},
                {title: "Parties (Plaintiff vs Defendant)"},
                {title: "Legal Counsel Lead", "class": "w160"},
                {title: "Financial Risk", "class": "w130 text-right"},
                {title: "Next Hearing", "class": "w110"},
                {title: "Status", "class": "w120 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    });
</script>
