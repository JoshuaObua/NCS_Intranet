<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="bar-chart-2" class="icon-16 mr5"></i> ICT & Media Department Executive Analytics & Operational Reports</h4>
        </div>
        <div class="card-body p30">
            <!-- TOP KPI STATS -->
            <div class="row mb30">
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-primary fw-bold mb5"><?php echo $total_equipment; ?></h2>
                        <span class="text-muted">Total IT & Media Hardware Items</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-success fw-bold mb5"><?php echo $available_equipment; ?></h2>
                        <span class="text-muted">Available in Central Vault</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-warning fw-bold mb5"><?php echo $issued_equipment; ?></h2>
                        <span class="text-muted">Checked Out / Issued</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p20 bg-light text-center border">
                        <h2 class="text-danger fw-bold mb5"><?php echo $open_tickets; ?></h2>
                        <span class="text-muted">Open IT Support Tickets</span>
                    </div>
                </div>
            </div>

            <!-- EQUIPMENT INVENTORY SUMMARY TABLE -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="cpu" class="icon-16"></i> IT & Media Hardware Valuation & Category Summary</h5>
            <div class="table-responsive mb30">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Item Code</th>
                            <th>Equipment Title</th>
                            <th>Category</th>
                            <th>Brand / Model</th>
                            <th>Condition</th>
                            <th class="text-right text-end">Purchase Cost (UGX)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($equipment)): ?>
                            <?php foreach ($equipment as $eq): ?>
                                <tr>
                                    <td><strong><?php echo $eq->item_code; ?></strong></td>
                                    <td><?php echo $eq->item_name; ?></td>
                                    <td><span class="badge bg-primary"><?php echo str_replace("_", " ", $eq->category); ?></span></td>
                                    <td><?php echo $eq->brand_model ?: 'N/A'; ?></td>
                                    <td><span class="badge bg-info"><?php echo $eq->condition_rating; ?></span></td>
                                    <td class="text-right text-end"><?php echo to_currency($eq->purchase_cost); ?></td>
                                    <td><span class="badge bg-success"><?php echo $eq->status; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted">No IT & Media equipment recorded.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- INTER-DEPARTMENTAL ISSUANCES LOG -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="send" class="icon-16"></i> Inter-Departmental Equipment Issuance Audit Log</h5>
            <div class="table-responsive mb30">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Dispatch Ref</th>
                            <th>Recipient Custodian</th>
                            <th>Department</th>
                            <th>Purpose / Deployment</th>
                            <th>Issue Date</th>
                            <th>Expected Return</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($issuances)): ?>
                            <?php foreach ($issuances as $iss): ?>
                                <tr>
                                    <td><strong><?php echo $iss->dispatch_ref; ?></strong></td>
                                    <td><?php echo $iss->recipient_name; ?></td>
                                    <td><?php echo $iss->target_department_name; ?></td>
                                    <td><?php echo $iss->purpose; ?></td>
                                    <td><?php echo format_to_date($iss->issue_date); ?></td>
                                    <td><?php echo format_to_date($iss->expected_return_date); ?></td>
                                    <td><span class="badge bg-warning"><?php echo $iss->status; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted">No equipment issuances logged.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- ICT & MEDIA OPERATIONAL EXPENSES -->
            <h5 class="fw-bold mb15 text-primary"><i data-feather="dollar-sign" class="icon-16"></i> ICT & Media Operational Expenses & Subscriptions</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Expense Ref</th>
                            <th>Title / Description</th>
                            <th>Category</th>
                            <th class="text-right text-end">Amount (UGX)</th>
                            <th>Expense Date</th>
                            <th>Vendor / Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($expenses)): ?>
                            <?php foreach ($expenses as $exp): ?>
                                <tr>
                                    <td><strong><?php echo $exp->expense_ref; ?></strong></td>
                                    <td><?php echo $exp->title; ?></td>
                                    <td><span class="badge bg-info"><?php echo str_replace("_", " ", $exp->category); ?></span></td>
                                    <td class="text-right text-end"><?php echo to_currency($exp->amount_ugx); ?></td>
                                    <td><?php echo format_to_date($exp->expense_date); ?></td>
                                    <td><?php echo $exp->vendor_supplier ?: 'N/A'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center text-muted">No IT expenses logged.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
