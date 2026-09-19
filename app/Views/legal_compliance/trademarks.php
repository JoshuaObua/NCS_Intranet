<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Registered Trademarks</div>
                <h3 class="fw-bold text-primary mb-0">3 Active IP Records</h3>
                <small class="text-muted">URSB Class 41 & 35 Protection</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">IP Licensing Revenue</div>
                <h3 class="fw-bold text-success mb-0">UGX 450M</h3>
                <small class="text-success">Merchandising & Media Rights</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Infringement Cease Desists</div>
                <h3 class="fw-bold text-info mb-0">0 Open Disputes</h3>
                <small class="text-muted">Active URSB Monitoring</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Renewal Due Date</div>
                <h3 class="fw-bold text-dark mb-0">2034-05-10</h3>
                <small class="text-success">10-Year Protection Period</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="award" class="icon-16 mr5"></i> Intellectual Property, Crest & Trademark Protection Ledger</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("legal_compliance/modal_trademark_form"), "<i data-feather='plus-circle' class='icon-16'></i> Register IP / Trademark", array("class" => "btn btn-primary", "title" => "Register Trademark")); ?>
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="trademarks-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#trademarks-table").appTable({
            source: '<?php echo get_uri("legal_compliance/trademarks_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Reg Ref", "class": "w120"},
                {title: "Trademark / Asset Name", "class": "w200"},
                {title: "URSB Class", "class": "w120"},
                {title: "Registration Date", "class": "w110"},
                {title: "Renewal Date", "class": "w110"},
                {title: "Status", "class": "w120 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    });
</script>
