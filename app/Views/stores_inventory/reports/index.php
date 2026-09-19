<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="bar-chart-2" class="icon-16 mr5"></i> Stores & Internal Audit Inventory Report</h4>
        </div>

        <div class="card-body">
            <!-- Category valuation overview -->
            <div class="row mb20">
                <div class="col-md-12">
                    <h5><i data-feather="grid" class="icon-16"></i> Inventory Category Valuation Summary</h5>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-light">
                                <th>Category</th>
                                <th>Total SKUs</th>
                                <th>Total Item Count</th>
                                <th>Total Inventory Valuation (UGX)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_val = 0;
                            foreach ($category_summary as $c) { 
                                $total_val += $c->category_value;
                            ?>
                            <tr>
                                <td><strong><?php echo ucfirst(str_replace("_", " ", $c->category)); ?></strong></td>
                                <td><?php echo $c->total_skus; ?></td>
                                <td><?php echo number_format($c->total_items); ?></td>
                                <td><strong><?php echo to_currency($c->category_value); ?></strong></td>
                            </tr>
                            <?php } ?>
                            <tr class="table-primary">
                                <td colspan="3"><strong>GRAND TOTAL STORE VALUATION</strong></td>
                                <td><strong><?php echo to_currency($total_val); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Stock Count & Accounting Reconciliation -->
            <div class="row">
                <div class="col-md-12">
                    <h5><i data-feather="check-circle" class="icon-16"></i> Accounting Stock Count Variance Brief</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th>Count Date</th>
                                <th>Item & SKU</th>
                                <th>Book Qty</th>
                                <th>Physical Qty</th>
                                <th>Variance</th>
                                <th>Variance Value (UGX)</th>
                                <th>Inspector</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($stock_takes)) { ?>
                                <tr><td colspan="7" class="text-center text-muted">No physical count variances recorded.</td></tr>
                            <?php } else { ?>
                                <?php foreach ($stock_takes as $st) { ?>
                                <tr>
                                    <td><?php echo format_to_date($st->take_date, false); ?></td>
                                    <td><?php echo $st->item_name; ?> (<code><?php echo $st->sku_code; ?></code>)</td>
                                    <td><?php echo $st->book_qty; ?></td>
                                    <td><?php echo $st->physical_qty; ?></td>
                                    <td><span class="badge bg-<?php echo ($st->variance == 0) ? 'success' : 'danger'; ?>"><?php echo $st->variance; ?></span></td>
                                    <td><?php echo to_currency($st->total_variance_value); ?></td>
                                    <td><?php echo $st->inspector_name ?: "-"; ?></td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
