<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Facility Capital Projects</div>
                <h3 class="fw-bold text-primary mb-0">8 Projects</h3>
                <small class="text-muted">Renovations & Expansion</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Active Contractor Sum</div>
                <h3 class="fw-bold text-success mb-0">UGX 1.45B</h3>
                <small class="text-success">Capital Commitments</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Facilities Linked</div>
                <h3 class="fw-bold text-info mb-0">100%</h3>
                <small class="text-muted">Direct Facility Mapping</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">AFCON 2027 Infrastructure</div>
                <h3 class="fw-bold text-dark mb-0">On Track</h3>
                <small class="text-success">Hoima Regional Hub</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="tool" class="icon-16 mr5"></i> Facility Capital Projects, Renovations & Contractor Register</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("projects/modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Create Facility Project", array("class" => "btn btn-primary", "title" => "Add Capital Project for Facility")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="facility-projects-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#facility-projects-table").appTable({
            source: '<?php echo get_uri("facilities/facility_projects_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Project Title & Scope"},
                {title: "Target Facility / Venue", "class": "w200"},
                {title: "Assigned Contractor", "class": "w200"},
                {title: "Contract Value (UGX)", "class": "w160 text-right"},
                {title: "Timeline Schedule", "class": "w180"},
                {title: "Status", "class": "w130 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w130"}
            ]
        });
    });
</script>
