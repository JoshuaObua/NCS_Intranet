<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="dollar-sign" class="icon-16 mr5"></i> Engineering Capital Expenditure (CapEx) Requisitions</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("engineering/capex_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> New CapEx Request", array("class" => "btn btn-primary", "title" => "Submit Engineering CapEx Requisition")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="engineering-capex-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#engineering-capex-table").appTable({
            source: '<?php echo get_uri("engineering/capex_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "CapEx Ref", "class": "w150"},
                {title: "Project Title"},
                {title: "Facility Location", "class": "w160"},
                {title: "Est. Budget", "class": "w130 text-right"},
                {title: "Requester", "class": "w150"},
                {title: "Submission Date", "class": "w120"},
                {title: "Workflow Status", "class": "w180 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
