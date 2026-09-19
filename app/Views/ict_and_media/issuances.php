<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="send" class="icon-16 mr5"></i> Equipment Inter-Departmental Issuances & Dispatch</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("ict_and_media/issuance_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Issue Equipment", array("class" => "btn btn-primary", "title" => "Dispatch / Issue IT Equipment")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="ict-issuances-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ict-issuances-table").appTable({
            source: '<?php echo get_uri("ict_and_media/issuances_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Dispatch Ref", "class": "w140"},
                {title: "Equipment Item"},
                {title: "Recipient Custodian", "class": "w160"},
                {title: "Target Department", "class": "w160"},
                {title: "Purpose / Event", "class": "w200"},
                {title: "Issue Date", "class": "w110"},
                {title: "Expected Return", "class": "w110"},
                {title: "Status", "class": "w110 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
