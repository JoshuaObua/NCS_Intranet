<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="file-text" class="icon-16 mr5"></i> <?php echo app_lang("goods_received_notes"); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("stores_inventory/grn_modal_form"), "<i data-feather='plus' class='icon-16'></i> " . app_lang("issue_grn"), array("class" => "btn btn-primary", "title" => "Issue Electronic GRN")); ?>
            </div>
        </div>

        <div class="card-body pt0">
            <table id="grn-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>GRN Number</th>
                        <th>PO / Contract Ref</th>
                        <th>Supplier / Vendor</th>
                        <th>Received Date</th>
                        <th>Total Value</th>
                        <th>Quality Inspection</th>
                        <th>Received By</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#grn-table").appTable({
            source: '<?php echo_uri("stores_inventory/grn_list_data"); ?>',
            columns: [
                {title: "GRN Number"},
                {title: "PO Ref"},
                {title: "Supplier"},
                {title: "Received Date"},
                {title: "Total Value"},
                {title: "Quality Inspection"},
                {title: "Received By"}
            ]
        });
    });
</script>
