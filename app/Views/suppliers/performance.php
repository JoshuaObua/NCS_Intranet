<div id="page-content" class="page-wrapper clearfix">
    <div class="card bg-white">
        <div class="page-title clearfix">
            <h1><i data-feather="bar-chart-2" class="icon-24"></i> Vendor Rating & Performance Scorecards</h1>
            <div class="title-button-group">
                <?php echo anchor(get_uri("suppliers"), "<i data-feather='truck' class='icon-16 mr5'></i> Back to Suppliers Registry", array("class" => "btn btn-outline-secondary")); ?>
            </div>
        </div>

        <div class="p20">
            <div class="alert alert-success">
                <i data-feather="award" class="icon-18"></i> <strong>Vendor Performance Rating System:</strong> Evaluates suppliers on timeliness of delivery, quality of goods/works, SLA compliance, and Form 5 procurement fulfillment under PPDA benchmarks.
            </div>

            <div class="table-responsive mt20">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr class="bg-light">
                            <th>Supplier Name</th>
                            <th>Category</th>
                            <th>Credit Terms</th>
                            <th>Performance Score</th>
                            <th>Delivery Rating</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $s) { ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc($s->company_name); ?></strong>
                                    <br/><small class="text-muted"><?php echo esc($s->supplier_code ?: 'N/A'); ?></small>
                                </td>
                                <td><span class="badge bg-soft-info text-info"><?php echo esc($s->category); ?></span></td>
                                <td><code><?php echo esc($s->payment_terms ?: 'Net 30 Days'); ?></code></td>
                                <td>
                                    <span class="text-warning fw-bold"><i data-feather="star" class="icon-16 fill-warning"></i> <?php echo number_format($s->rating, 1); ?> / 5.0</span>
                                </td>
                                <td>
                                    <?php if ($s->rating >= 4.5) { ?>
                                        <span class="badge bg-success">EXCELLENT (A+)</span>
                                    <?php } else if ($s->rating >= 3.5) { ?>
                                        <span class="badge bg-info">GOOD (B)</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">POOR / DEBARRED</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <?php echo anchor(get_uri("suppliers/view/" . $s->id), "<i data-feather='eye' class='icon-16'></i> View Scorecard", array("class" => "btn btn-sm btn-outline-info")); ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
