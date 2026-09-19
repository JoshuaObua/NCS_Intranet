<div id="page-content" class="page-wrapper clearfix">
    <div class="card bg-white">
        <div class="page-title clearfix">
            <h1><i data-feather="shield" class="icon-24"></i> PPDA & Statutory Compliance Matrix</h1>
            <div class="title-button-group">
                <?php echo anchor(get_uri("suppliers"), "<i data-feather='truck' class='icon-16 mr5'></i> Back to Suppliers Registry", array("class" => "btn btn-outline-secondary")); ?>
            </div>
        </div>

        <div class="p20">
            <div class="alert alert-info">
                <i data-feather="info" class="icon-18"></i> <strong>PPDA & URA Statutory Verification Matrix:</strong> Tracks Public Procurement and Disposal of Public Assets Authority (PPDA) registration numbers, Uganda Revenue Authority (URA) TIN numbers, VAT registration, and blacklisting debarment status under Public Finance Management Act (PFMA 2015).
            </div>

            <div class="table-responsive mt20">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr class="bg-light">
                            <th>Supplier Name</th>
                            <th>Category</th>
                            <th>PPDA Registration No.</th>
                            <th>URA TIN Number</th>
                            <th>VAT Number</th>
                            <th>Compliance Status</th>
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
                                <td><code><?php echo esc($s->ppda_registration_no ?: 'NOT REGISTERED'); ?></code></td>
                                <td><strong><?php echo esc($s->tin_number ?: 'NOT FILED'); ?></strong></td>
                                <td><small><?php echo esc($s->vat_number ?: 'N/A'); ?></small></td>
                                <td>
                                    <?php if ($s->is_blacklisted) { ?>
                                        <span class="badge bg-danger"><i data-feather="alert-triangle" class="icon-14"></i> DEBARRED BY PPDA</span>
                                    <?php } else if ($s->prequalification_status == "PRE_QUALIFIED") { ?>
                                        <span class="badge bg-success"><i data-feather="check-circle" class="icon-14"></i> FULLY COMPLIANT</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning text-dark"><i data-feather="clock" class="icon-14"></i> PROVISIONAL</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center">
                                    <?php echo anchor(get_uri("suppliers/view/" . $s->id), "<i data-feather='eye' class='icon-16'></i> View Profile", array("class" => "btn btn-sm btn-outline-info")); ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
