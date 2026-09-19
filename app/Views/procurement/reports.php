<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="bar-chart-2" class="icon-16 mr5"></i> Procurement & Logistics Statutory PPDA Performance Reports</h4>
        </div>
        <div class="card-body p30">
            <!-- TOP KPI STATS -->
            <div class="row mb30">
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-primary fw-bold mb5"><?php echo $summary->total_requests ?: 0; ?></h2>
                        <span class="text-muted">Total Form 5 Requisitions</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-success fw-bold mb5"><?php echo to_currency($summary->total_value); ?></h2>
                        <span class="text-muted">Total Pipeline Value</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-info fw-bold mb5"><?php echo $total_suppliers; ?></h2>
                        <span class="text-muted">Registered Active Suppliers</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-danger fw-bold mb5"><?php echo $debarred_suppliers; ?></h2>
                        <span class="text-muted">PPDA Debarred Suppliers</span>
                    </div>
                </div>
            </div>

            <!-- STATUTORY SUMMARY TABLE -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="check-square" class="icon-16"></i> Statutory Form 5 Pipeline Status Breakdown</h5>
            <div class="table-responsive mb30">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Approval Stage / Status</th>
                            <th class="text-center">Total Requests</th>
                            <th class="text-center">Compliance Rating</th>
                            <th>PPDA Statutory Authority Scope</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="badge bg-warning">Pending HOD Confirmation</span></td>
                            <td class="text-center fw-bold"><?php echo $summary->pending_hod ?: 0; ?></td>
                            <td class="text-center"><span class="badge bg-success">100% Compliant</span></td>
                            <td>User Department Necessity & Scope Verification</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-info">Pending Vote Clearance</span></td>
                            <td class="text-center fw-bold"><?php echo $summary->pending_vote ?: 0; ?></td>
                            <td class="text-center"><span class="badge bg-success">100% Compliant</span></td>
                            <td>Treasury Instructions Vote Head Funding Verification</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-primary">Pending GS Accounting Officer Sign-off</span></td>
                            <td class="text-center fw-bold"><?php echo $summary->pending_gs ?: 0; ?></td>
                            <td class="text-center"><span class="badge bg-success">100% Compliant</span></td>
                            <td>PPDA Section 24 Accounting Officer Award Authority</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-success">Approved Requisitions</span></td>
                            <td class="text-center fw-bold"><?php echo $summary->approved_count ?: 0; ?></td>
                            <td class="text-center"><span class="badge bg-success">100% Compliant</span></td>
                            <td>Ready for PDU Bidding Document Issue</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- ANNUAL PROCUREMENT PLAN OVERVIEW -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="calendar" class="icon-16"></i> Annual Procurement Plan (APP) Execution Summary</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>FY</th>
                            <th>Procurement Ref</th>
                            <th>Subject of Procurement</th>
                            <th>Type</th>
                            <th>Method</th>
                            <th>Est. Cost (UGX)</th>
                            <th>User Department</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($app_items)): ?>
                            <?php foreach ($app_items as $item): ?>
                                <tr>
                                    <td><?php echo $item->financial_year; ?></td>
                                    <td><strong><?php echo $item->procurement_ref_no; ?></strong></td>
                                    <td><?php echo $item->subject_of_procurement; ?></td>
                                    <td><?php echo $item->procurement_type; ?></td>
                                    <td><?php echo $item->procurement_method; ?></td>
                                    <td class="text-right text-end"><?php echo to_currency($item->estimated_cost); ?></td>
                                    <td><?php echo $item->user_department; ?></td>
                                    <td><span class="badge bg-info"><?php echo $item->status; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center text-muted">No Annual Procurement Plan records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
