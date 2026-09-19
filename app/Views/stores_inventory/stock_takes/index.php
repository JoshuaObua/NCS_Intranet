<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="check-circle" class="icon-16 mr5"></i> <?php echo app_lang("stock_takes_and_reconciliation"); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("stores_inventory/stock_take_modal_form"), "<i data-feather='plus' class='icon-16'></i> Record Physical Count", array("class" => "btn btn-primary", "title" => "Record Physical Stock Count & Reconciliation")); ?>
            </div>
        </div>

        <div class="card-body pt0">
            <table id="stock-takes-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Count Date</th>
                        <th>Item & SKU</th>
                        <th>Book Quantity</th>
                        <th>Physical Count</th>
                        <th>Variance</th>
                        <th>Variance Value (UGX)</th>
                        <th>Inspector</th>
                        <th>Reconciliation Notes</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#stock-takes-table").appTable({
            source: '<?php echo_uri("stores_inventory/stock_takes_list_data"); ?>',
            columns: [
                {title: "Count Date"},
                {title: "Item"},
                {title: "Book Qty"},
                {title: "Physical Count"},
                {title: "Variance"},
                {title: "Variance Value"},
                {title: "Inspector"},
                {title: "Reconciliation Notes"}
            ]
        });
    });
</script>
