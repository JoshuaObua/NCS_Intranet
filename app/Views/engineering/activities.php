<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="activity" class="icon-16 mr5"></i> Senior Engineer HOD Personal Activity Timeline & Audit Log</h4>
        </div>
        <div class="card-body p30">
            <div class="alert alert-info">
                <strong><i data-feather="info" class="icon-16"></i> HOD Accountability Audit Log</strong><br>
                Displays personal action history, CapEx approval decisions, Work Order dispatches, and statutory inspection certificates issued by the Senior Engineer HOD.
            </div>

            <!-- TIMELINE LIST -->
            <div class="activity-timeline mt20">
                <?php if (count($capex_list)): ?>
                    <?php foreach ($capex_list as $capex): ?>
                        <div class="d-flex mb20 border-bottom pb15">
                            <div class="flex-shrink-0 bg-primary text-white p10 rounded mr15" style="height: 40px; width: 40px; text-align: center;">
                                <i data-feather="dollar-sign" class="icon-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb5 fw-bold">CapEx Decision: <?php echo $capex->project_title; ?> (Ref: <?php echo $capex->capex_ref_no; ?>)</h6>
                                <p class="mb5 text-muted">Budget: <strong><?php echo to_currency($capex->estimated_budget); ?></strong> | Location: <?php echo $capex->facility_location; ?></p>
                                <span class="badge bg-info">Status: <?php echo $capex->status; ?></span>
                                <small class="text-muted ml10"><i data-feather="clock" class="icon-12"></i> <?php echo format_to_relative_time($capex->created_at); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (count($inspections)): ?>
                    <?php foreach ($inspections as $insp): ?>
                        <div class="d-flex mb20 border-bottom pb15">
                            <div class="flex-shrink-0 bg-success text-white p10 rounded mr15" style="height: 40px; width: 40px; text-align: center;">
                                <i data-feather="check-square" class="icon-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb5 fw-bold">Facility Inspection Conducted: <?php echo $insp->event_or_facility; ?></h6>
                                <p class="mb5 text-muted">Readiness Score: <strong><?php echo $insp->readiness_score; ?>%</strong> | Findings: <?php echo $insp->findings; ?></p>
                                <span class="badge bg-success">Certification: <?php echo $insp->status; ?></span>
                                <small class="text-muted ml10"><i data-feather="clock" class="icon-12"></i> <?php echo format_to_date($insp->inspection_date); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (count($work_orders)): ?>
                    <?php foreach ($work_orders as $wo): ?>
                        <div class="d-flex mb20 border-bottom pb15">
                            <div class="flex-shrink-0 bg-warning text-white p10 rounded mr15" style="height: 40px; width: 40px; text-align: center;">
                                <i data-feather="tool" class="icon-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb5 fw-bold">Work Order Action: <?php echo $wo->title; ?> (Ref: <?php echo $wo->wo_number; ?>)</h6>
                                <p class="mb5 text-muted">Category: <?php echo $wo->category; ?> | Venue: <?php echo $wo->facility_location; ?> | Priority: <?php echo $wo->priority; ?></p>
                                <span class="badge bg-secondary">Status: <?php echo $wo->status; ?></span>
                                <small class="text-muted ml10"><i data-feather="clock" class="icon-12"></i> <?php echo format_to_relative_time($wo->created_at); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
