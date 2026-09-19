<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Active Board Packages</div>
                <h3 class="fw-bold text-primary mb-0">4</h3>
                <small class="text-muted">FY 2026/27 Q1 Sittings</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Cabinet Papers</div>
                <h3 class="fw-bold text-success mb-0">1 Brief</h3>
                <small class="text-success">Land & Titling Security</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Emergency Resolutions</div>
                <h3 class="fw-bold text-warning mb-0">1</h3>
                <small class="text-warning">AFCON 2027 Infrastructure</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Ministry Submissions</div>
                <h3 class="fw-bold text-dark mb-0">100% On-Time</h3>
                <small class="text-muted">MOES & MoFPED Submissions</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="file-text" class="icon-16 mr5"></i> Executive Board & Ministry Policy Brief Packages</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("administration/generate_board_package_modal"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Create Board Package Brief", array("class" => "btn btn-primary", "title" => "Create Executive Board Package Brief")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="admin-board-packages-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#admin-board-packages-table").appTable({
            source: '<?php echo get_uri("administration/board_packages_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Ref Code", "class": "w130"},
                {title: "Policy Package Title & Executive Summary"},
                {title: "Board Sitting", "class": "w140"},
                {title: "Category", "class": "w160"},
                {title: "Lead Author", "class": "w160"},
                {title: "Target Date", "class": "w110"},
                {title: "Security Level", "class": "w140 text-center"},
                {title: "Status", "class": "w150 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
