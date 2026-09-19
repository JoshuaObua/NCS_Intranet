<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Asset Revaluations</div>
                <h3 class="fw-bold text-primary mb-0">297 Assets</h3>
                <small class="text-muted">IPSAS 17 Fair Value Model</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Land Portfolio Revaluation</div>
                <h3 class="fw-bold text-success mb-0">UGX 27.89B</h3>
                <small class="text-success">Coronation Ave & Regional Hubs</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Buildings & Infra Revaluation</div>
                <h3 class="fw-bold text-info mb-0">UGX 2.31B</h3>
                <small class="text-muted">Office Floors, Gyms & Pavilions</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Equipment & Vehicles</div>
                <h3 class="fw-bold text-dark mb-0">UGX 1.98B</h3>
                <small class="text-muted">Fleet, Electrical & ICT Hardware</small>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="page-title clearfix">
            <h4><i data-feather="edit-3" class="icon-16 mr5"></i> Category Valuation & Revaluation Portfolio Breakdown</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Asset Class / Subcategory</th>
                            <th>Total Asset Count</th>
                            <th>Baseline FB_COST (UGX)</th>
                            <th>Revalued / Adjusted Cost (UGX)</th>
                            <th>Net Book Value (NBV UGX)</th>
                            <th>Revaluation Variance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat) { ?>
                            <tr>
                                <td><strong><?php echo $cat->category_segment3; ?></strong></td>
                                <td><span class="badge bg-secondary"><?php echo $cat->asset_count; ?> Assets</span></td>
                                <td class="text-right"><?php echo to_currency($cat->total_fb_cost); ?></td>
                                <td class="text-right fw-bold text-primary"><?php echo to_currency($cat->total_adjusted_cost); ?></td>
                                <td class="text-right text-success"><?php echo to_currency($cat->total_nbv); ?></td>
                                <td class="text-center">
                                    <?php 
                                    $diff = $cat->total_adjusted_cost - $cat->total_fb_cost;
                                    if ($diff > 0) {
                                        echo "<span class='badge bg-success'>+" . to_currency($diff) . "</span>";
                                    } else if ($diff < 0) {
                                        echo "<span class='badge bg-danger'>" . to_currency($diff) . "</span>";
                                    } else {
                                        echo "<span class='badge bg-light text-dark'>0.00 Parity</span>";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
