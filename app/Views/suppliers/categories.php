<div id="page-content" class="page-wrapper clearfix">
    <div class="card bg-white">
        <div class="page-title clearfix">
            <h1><i data-feather="grid" class="icon-24"></i> Supplier Categories & Pre-qualification Tiers</h1>
            <div class="title-button-group">
                <?php echo anchor(get_uri("suppliers"), "<i data-feather='truck' class='icon-16 mr5'></i> Back to Suppliers Registry", array("class" => "btn btn-outline-secondary")); ?>
            </div>
        </div>

        <div class="p20">
            <div class="row">
                <div class="col-md-4">
                    <div class="card p15 border shadow-sm mb15 bg-light">
                        <h6 class="text-primary fw-bold mb-2"><i data-feather="tool" class="icon-16"></i> Works & Civil Construction</h6>
                        <p class="text-muted small mb-2">Stadium construction, facility renovations, civil maintenance & structural engineering.</p>
                        <span class="badge bg-primary">Active Suppliers</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p15 border shadow-sm mb15 bg-light">
                        <h6 class="text-success fw-bold mb-2"><i data-feather="package" class="icon-16"></i> Sports Equipment & Apparel</h6>
                        <p class="text-muted small mb-2">FIFA/WADA compliant sports gear, uniforms, turf maintenance & athletic equipment.</p>
                        <span class="badge bg-success">Active Suppliers</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p15 border shadow-sm mb15 bg-light">
                        <h6 class="text-info fw-bold mb-2"><i data-feather="cpu" class="icon-16"></i> ICT & Broadcast Tech</h6>
                        <p class="text-muted small mb-2">Server infrastructure, VAR cameras, stadium Wi-Fi, timing equipment & broadcasting.</p>
                        <span class="badge bg-info">Active Suppliers</span>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt20">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr class="bg-light">
                            <th>Supplier Name</th>
                            <th>Category</th>
                            <th>City / Country</th>
                            <th>Pre-qualification Tier</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $s) { ?>
                            <tr>
                                <td><strong><?php echo esc($s->company_name); ?></strong></td>
                                <td><span class="badge bg-soft-info text-info"><?php echo esc($s->category); ?></span></td>
                                <td><?php echo esc(($s->city ?: 'Kampala') . ', ' . ($s->country ?: 'Uganda')); ?></td>
                                <td>
                                    <?php if ($s->prequalification_status == "PRE_QUALIFIED") { ?>
                                        <span class="badge bg-success">Tier 1 - Pre-Qualified</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark">Tier 2 - Provisional</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <?php echo anchor(get_uri("suppliers/view/" . $s->id), "<i data-feather='eye' class='icon-16'></i> Profile", array("class" => "btn btn-sm btn-outline-info")); ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
