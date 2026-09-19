<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Registered Federations</div>
                <h3 class="fw-bold text-primary mb-0">51</h3>
                <small class="text-muted">Recognized Under Sports Act 2023</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Annual Subvention Budget</div>
                <h3 class="fw-bold text-success mb-0">UGX 29.80B</h3>
                <small class="text-success">Government Support</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Fully Compliant Federations</div>
                <h3 class="fw-bold text-info mb-0">42</h3>
                <small class="text-muted">Statutory Returns Verified</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Under Governance Normalization</div>
                <h3 class="fw-bold text-warning mb-0">3</h3>
                <small class="text-warning">Interim Committees</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="flag" class="icon-16 mr5"></i> National Sports Associations & Federations Technical Oversight</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("administration/federation_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Register New Federation", array("class" => "btn btn-primary", "title" => "Register National Sports Federation")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="admin-federations-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#admin-federations-table").appTable({
            source: '<?php echo get_uri("administration/federations_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Code", "class": "w90"},
                {title: "Federation Name & Affiliation"},
                {title: "Category", "class": "w160"},
                {title: "Executive Bureau", "class": "w200"},
                {title: "Annual Grant Allocation", "class": "w150 text-right"},
                {title: "Disbursed YTD", "class": "w140 text-right"},
                {title: "Governance Status", "class": "w160 text-center"},
                {title: "Compliance", "class": "w100 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
