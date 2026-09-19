<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Disputes Logged</div>
                <h3 class="fw-bold text-primary mb-0">3 Disputes</h3>
                <small class="text-muted">Federation Elections & Governance</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Active Arbitration</div>
                <h3 class="fw-bold text-warning mb-0">1 Pending Hearing</h3>
                <small class="text-warning">Uganda Netball Federation</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Resolved Disputes</div>
                <h3 class="fw-bold text-success mb-0">2 Resolved</h3>
                <small class="text-success">NCS Act Tribunal Enforced</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">CAS / Tribunal Referrals</div>
                <h3 class="fw-bold text-info mb-0">0 Escaped</h3>
                <small class="text-muted">Domestic Resolution First</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="shield-off" class="icon-16 mr5"></i> Federation Governance, Arbitration & Tribunal Disputes</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("legal_compliance/modal_dispute_form"), "<i data-feather='plus-circle' class='icon-16'></i> Log New Federation Dispute", array("class" => "btn btn-primary", "title" => "Log Federation Dispute")); ?>
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="disputes-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#disputes-table").appTable({
            source: '<?php echo get_uri("legal_compliance/disputes_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Dispute Ref", "class": "w120"},
                {title: "Federation / Entity Name", "class": "w180"},
                {title: "Dispute Subject & Description"},
                {title: "Arbitrator / Panel", "class": "w160"},
                {title: "Hearing Date", "class": "w110"},
                {title: "Status", "class": "w120 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    });
</script>
