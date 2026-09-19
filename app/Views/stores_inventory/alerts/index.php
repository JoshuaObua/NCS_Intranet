<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="bell" class="icon-16 mr5"></i> <?php echo app_lang("stock_aging_and_alerts"); ?></h4>
        </div>

        <div class="card-body">
            <h5 class="text-danger mb15"><i data-feather="alert-triangle" class="icon-16"></i> Low Stock Buffer Alerts</h5>
            <?php if (empty($low_stock_items)) { ?>
                <div class="alert alert-success">All store inventory SKUs are operating above safety buffer thresholds.</div>
            <?php } else { ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th>SKU Code</th>
                                <th>Item Name</th>
                                <th>Qty on Hand</th>
                                <th>Min Reorder Threshold</th>
                                <th>Bin Location</th>
                                <th>Action Required</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($low_stock_items as $item) { ?>
                            <tr>
                                <td><code><?php echo $item->sku_code; ?></code></td>
                                <td><strong><?php echo $item->item_name; ?></strong></td>
                                <td><span class="badge bg-danger fs-6"><?php echo $item->quantity_on_hand; ?></span></td>
                                <td><?php echo $item->min_reorder_level; ?></td>
                                <td><?php echo $item->warehouse_bin_location; ?></td>
                                <td>
                                    <?php echo modal_anchor(get_uri("stores_inventory/grn_modal_form"), "<i data-feather='plus-circle' class='icon-14'></i> Reorder Stock (GRN)", array("class" => "btn btn-sm btn-outline-primary")); ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
