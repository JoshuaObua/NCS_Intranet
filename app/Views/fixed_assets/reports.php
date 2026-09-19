<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Net Asset Register</div>
                <h3 class="fw-bold text-primary mb-0">UGX 32.18B</h3>
                <small class="text-muted">297 Appraised Assets</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Land & Property Portfolio</div>
                <h3 class="fw-bold text-success mb-0">UGX 27.89B</h3>
                <small class="text-success">8 Land Holdings (Plots 2-10 Coronation)</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Buildings & Infrastructures</div>
                <h3 class="fw-bold text-info mb-0">UGX 2.31B</h3>
                <small class="text-muted">18 Structures (Non-Res & Hostels)</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Fleet & Equipment Register</div>
                <h3 class="fw-bold text-dark mb-0">UGX 1.98B</h3>
                <small class="text-success">271 Machinery & Equipment Assets</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="bar-chart-2" class="icon-16 mr5"></i> IPSAS 17 & Auditor General Fixed Asset Statutory Financial Reports</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="window.print();"><i data-feather="printer" class="icon-16"></i> Export / Print Board Package</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card border p-3">
                        <h5 class="fw-bold text-dark"><i data-feather="pie-chart" class="icon-16 text-primary me-2"></i> Category Portfolio Distribution</h5>
                        <p class="text-muted small">Summary valuation across 11 Excel worksheet classes.</p>
                        <table class="table table-sm text-sm">
                            <thead>
                                <tr>
                                    <th>Class / Subcategory</th>
                                    <th>Asset Count</th>
                                    <th>Revalued Valuation (UGX)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $cat) { ?>
                                    <tr>
                                        <td><?php echo $cat->category_segment3; ?></td>
                                        <td><?php echo $cat->asset_count; ?></td>
                                        <td class="text-right fw-bold"><?php echo to_currency($cat->total_adjusted_cost); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card border p-3">
                        <h5 class="fw-bold text-dark"><i data-feather="shield" class="icon-16 text-success me-2"></i> Statutory Audit Compliance Index</h5>
                        <p class="text-muted small">Status of quarterly compliance reporting across statutory standards.</p>
                        <table class="table table-sm text-sm">
                            <thead>
                                <tr>
                                    <th>Statutory Framework</th>
                                    <th>Compliance Rating</th>
                                    <th>Audit Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>IPSAS 17 Property Plant & Equipment</td>
                                    <td>100%</td>
                                    <td><span class="badge bg-success">PASSED</span></td>
                                </tr>
                                <tr>
                                    <td>Public Finance Management Act (PFMA 2015)</td>
                                    <td>100%</td>
                                    <td><span class="badge bg-success">PASSED</span></td>
                                </tr>
                                <tr>
                                    <td>Treasury Instructions 2017 Asset Rules</td>
                                    <td>100%</td>
                                    <td><span class="badge bg-success">PASSED</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
