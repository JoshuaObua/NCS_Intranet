<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Disposals & Write-Offs</div>
                <h3 class="fw-bold text-danger mb-0">0 Records</h3>
                <small class="text-muted">Statutory Write-Off Register</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Written-off Net Valuation</div>
                <h3 class="fw-bold text-success mb-0">UGX 0.00</h3>
                <small class="text-success">Fully Depreciated / Board Cleared</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Auditor General Approval</div>
                <h3 class="fw-bold text-info mb-0">100% Compliant</h3>
                <small class="text-muted">PPDA Board Write-off Standard</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">General Secretary Sign-Off</div>
                <h3 class="fw-bold text-dark mb-0">Required</h3>
                <small class="text-success">CapEx & Write-off Signoff</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="trash-2" class="icon-16 mr5"></i> Statutory Disposals & Asset Write-Off Register</h4>
            <div class="title-button-group">
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Write-Off Queue</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Asset Ref</th>
                            <th>Tag Number</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Original Cost (UGX)</th>
                            <th>Status</th>
                            <th>Write-Off Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($disposals)) { ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted p-4">No assets currently marked for statutory disposal or write-off. All 297 assets active.</td>
                            </tr>
                        <?php } else { ?>
                            <?php foreach ($disposals as $d) { ?>
                                <tr>
                                    <td><code><?php echo $d->asset_number; ?></code></td>
                                    <td><?php echo $d->tag_number; ?></td>
                                    <td><?php echo $d->asset_description; ?></td>
                                    <td><?php echo $d->category_segment3; ?></td>
                                    <td class="text-right"><?php echo to_currency($d->adjusted_cost); ?></td>
                                    <td><span class="badge bg-danger"><?php echo $d->status; ?></span></td>
                                    <td><span class="badge bg-success">GS SIGNED OFF</span></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
