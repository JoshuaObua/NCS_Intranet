<div class="modal-header">
    <h5 class="modal-title"><i data-feather="file-text" class="icon-16 mr5"></i> PPDA Form 5: <?php echo $model_info->procurement_ref_no; ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body clearfix p20">
    <div class="row mb15">
        <div class="col-md-6">
            <strong>Procurement Reference:</strong> <?php echo $model_info->procurement_ref_no; ?><br>
            <strong>Procurement Type:</strong> <?php echo $model_info->procurement_type; ?><br>
            <strong>Financial Year:</strong> <?php echo $model_info->financial_year; ?><br>
            <strong>Budget Category:</strong> <?php echo $model_info->budget_category; ?>
        </div>
        <div class="col-md-6 text-right text-end">
            <strong>Status:</strong> 
            <?php 
            if ($model_info->status === "SUBMITTED") {
                echo "<span class='badge bg-warning'>Submitted (Internal Dept)</span>";
            } else if ($model_info->status === "HOD_CONFIRMED") {
                echo "<span class='badge bg-info'>HOD Endorsed</span>";
            } else if ($model_info->status === "FORWARDED_DEPT") {
                echo "<span class='badge bg-primary'>Forwarded Inter-Dept</span>";
            } else if ($model_info->status === "VOTE_CLEARED") {
                echo "<span class='badge bg-info'>Vote Cleared</span>";
            } else if ($model_info->status === "APPROVED") {
                echo "<span class='badge bg-success'>APPROVED</span>";
            } else {
                echo "<span class='badge bg-danger'>REJECTED</span>";
            }
            ?><br>
            <strong>Submitted Date:</strong> <?php echo format_to_date($model_info->requested_at); ?><br>
            <strong>Requester:</strong> <?php echo $model_info->requester_name; ?>
        </div>
    </div>

    <hr>

    <div class="mb15">
        <strong>Subject of Procurement:</strong><br>
        <p class="text-muted"><?php echo nl2br($model_info->subject_of_procurement); ?></p>
    </div>

    <div class="row mb15">
        <div class="col-md-6">
            <strong>Procurement Plan Ref:</strong> <?php echo $model_info->procurement_plan_ref; ?><br>
            <strong>Delivery Location:</strong> <?php echo $model_info->location_for_delivery; ?>
        </div>
        <div class="col-md-6">
            <strong>Required Delivery Date:</strong> <?php echo format_to_date($model_info->date_required); ?><br>
            <strong>Multi-Year Contract:</strong> <?php echo $model_info->is_multiyear ? "Yes" : "No"; ?>
        </div>
    </div>

    <h6 class="fw-bold mb10">Itemized Specifications & Costs:</h6>
    <div class="table-responsive mb20">
        <table class="table table-bordered table-sm">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>UOM</th>
                    <th>Est. Unit Cost</th>
                    <th>Market Price</th>
                    <th>Line Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo $item->item_no; ?></td>
                        <td><?php echo $item->description; ?></td>
                        <td><?php echo $item->quantity; ?></td>
                        <td><?php echo $item->unit_of_measure; ?></td>
                        <td><?php echo to_currency($item->estimated_unit_cost); ?></td>
                        <td><?php echo to_currency($item->market_price); ?></td>
                        <td><?php echo to_currency($item->line_total_cost); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-right text-end mb20">
        <h5>Grand Total Estimated Cost: <span class="text-success fw-bold"><?php echo to_currency($model_info->grand_total_estimated_cost); ?></span></h5>
    </div>

    <!-- WORKFLOW HISTORY AUDIT TRAIL -->
    <h6 class="fw-bold mb10 border-top pt15 text-primary"><i data-feather="activity" class="icon-16"></i> Bureaucracy Approval & Forwarding Audit Trail:</h6>
    <?php if (count($workflow_history)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-sm small">
                <thead class="bg-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>Action</th>
                        <th>From Officer / Department</th>
                        <th>Forwarded To / Target</th>
                        <th>Remarks / Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($workflow_history as $step): ?>
                        <tr>
                            <td><?php echo get_array_value($step, "timestamp"); ?></td>
                            <td><span class="badge bg-secondary"><?php echo get_array_value($step, "action"); ?></span></td>
                            <td><?php echo get_array_value($step, "from_user"); ?> (<?php echo get_array_value($step, "from_dept"); ?>)</td>
                            <td><?php echo get_array_value($step, "to_dept") ? get_array_value($step, "to_dept") : (get_array_value($step, "to_approver") ? "Approver: " . get_array_value($step, "to_approver") : "N/A"); ?></td>
                            <td><?php echo get_array_value($step, "comments"); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted small">No workflow history logged yet.</p>
    <?php endif; ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>
