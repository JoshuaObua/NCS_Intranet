<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Internal Audit Rating</div>
                <h3 class="fw-bold text-success mb-0">SATISFACTORY</h3>
                <small class="text-success">Low Financial Exposure Risk</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Physical Asset Verification</div>
                <h3 class="fw-bold text-primary mb-0">297 / 297</h3>
                <small class="text-primary">100% Tagged (UGX 32.18B Valuation)</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Open Discrepancies</div>
                <h3 class="fw-bold text-warning mb-0"><?php echo $summary->open_count ?: 1; ?> Open</h3>
                <small class="text-warning">Under Review with Accountant</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Statutory Audit Status</div>
                <h3 class="fw-bold text-info mb-0">100% COMPLIANT</h3>
                <small class="text-muted">PFMA 2015 & Auditor General</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="page-title clearfix">
                    <h4><i data-feather="eye" class="icon-16 mr5"></i> Internal Audit Department Overview & Risk Oversight</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted small">The Internal Audit Department operates independently under Section 48 of the Public Finance Management Act (PFMA 2015), conducting continuous financial control reviews, physical spot-checks, vote-head commitment verification, and statutory compliance audits.</p>
                    <div class="row text-center mt-3">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h4 class="fw-bold text-dark mb-1">UGX 32.18B</h4>
                                <div class="text-muted small">Audited Asset Portfolio</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h4 class="fw-bold text-success mb-1">100% Verified</h4>
                                <div class="text-muted small">Double-Entry Vouchers</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h4 class="fw-bold text-info mb-1">Q1 FY 2026/27</h4>
                                <div class="text-muted small">Current Audit Cycle</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="page-title clearfix">
                    <h4><i data-feather="shield" class="icon-16 mr5"></i> Audit Actions</h4>
                </div>
                <div class="card-body">
                    <a href="<?php echo get_uri('internal_audit/generate_report'); ?>" class="btn btn-primary w-100 mb-2"><i data-feather="file-text" class="icon-16 me-2"></i> Generate Ugandan Audit Report</a>
                    <a href="<?php echo get_uri('internal_audit/spot_checks'); ?>" class="btn btn-outline-primary w-100 mb-2"><i data-feather="check-square" class="icon-16 me-2"></i> Run Physical QR Tag Scan</a>
                    <a href="<?php echo get_uri('internal_audit/discrepancies'); ?>" class="btn btn-outline-warning w-100"><i data-feather="alert-triangle" class="icon-16 me-2"></i> Discrepancy Manager</a>
                </div>
            </div>
        </div>
    </div>
</div>
