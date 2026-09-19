<div id="page-content" class="page-wrapper clearfix">
    <div class="card bg-white">
        <div class="page-title clearfix">
            <h1><i data-feather="truck" class="icon-24"></i> <?php echo esc($model_info->company_name); ?></h1>
            <div class="title-button-group">
                <?php echo anchor(get_uri("suppliers"), "<i data-feather='arrow-left' class='icon-16 mr5'></i> Back to Suppliers Registry", array("class" => "btn btn-outline-secondary")); ?>
                <?php echo modal_anchor(get_uri("suppliers/contact_modal_form"), "<i data-feather='user-plus' class='icon-16 mr5'></i> Add Contact Person", array("class" => "btn btn-outline-primary", "title" => "Add Contact Person", "data-post-supplier_id" => $model_info->id)); ?>
                <?php echo modal_anchor(get_uri("suppliers/modal_form"), "<i data-feather='edit' class='icon-16 mr5'></i> Edit Supplier Profile", array("class" => "btn btn-primary", "title" => "Edit Supplier Profile", "data-post-id" => $model_info->id)); ?>
            </div>
        </div>

        <div class="p20">
            <!-- Header Badges -->
            <div class="row mb20">
                <div class="col-md-8">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span class="badge bg-soft-info text-info p-2" style="font-size:13px;"><i data-feather="grid" class="icon-14"></i> <?php echo esc($model_info->category); ?></span>
                        <span class="badge bg-light text-dark border p-2" style="font-size:13px;"><i data-feather="hash" class="icon-14"></i> Code: <?php echo esc($model_info->supplier_code ?: 'N/A'); ?></span>
                        <?php if ($model_info->is_blacklisted) { ?>
                            <span class="badge bg-danger p-2" style="font-size:13px;"><i data-feather="alert-triangle" class="icon-14"></i> BLACKLISTED / DEBARRED</span>
                        <?php } else { ?>
                            <span class="badge bg-success p-2" style="font-size:13px;"><i data-feather="check-circle" class="icon-14"></i> PRE-QUALIFIED VENDOR</span>
                        <?php } ?>
                        <span class="badge bg-warning text-dark p-2" style="font-size:13px;"><i data-feather="star" class="icon-14 fill-warning"></i> Vendor Rating: <?php echo number_format($model_info->rating, 1); ?> / 5.0</span>
                    </div>
                </div>
            </div>

            <!-- Profile Summary & Details Grid -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card p15 border shadow-sm mb20 bg-light">
                        <h6 class="text-primary fw-bold mb-3"><i data-feather="briefcase" class="icon-16"></i> Company Profile & Contact Info</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="w150 text-muted">Company Name:</td>
                                <td><strong><?php echo esc($model_info->company_name); ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Telephone:</td>
                                <td><i data-feather="phone" class="icon-14"></i> <?php echo esc($model_info->phone ?: 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email Address:</td>
                                <td><i data-feather="mail" class="icon-14"></i> <?php echo esc($model_info->email ?: 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Website:</td>
                                <td><a href="<?php echo esc($model_info->website); ?>" target="_blank"><?php echo esc($model_info->website ?: 'N/A'); ?></a></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Physical Address:</td>
                                <td><?php echo esc($model_info->address ?: 'Kampala, Uganda'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card p15 border shadow-sm mb20 bg-light">
                        <h6 class="text-success fw-bold mb-3"><i data-feather="shield" class="icon-16"></i> Statutory & Banking Details</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="w150 text-muted">PPDA Reg. No:</td>
                                <td><span class="badge bg-dark"><?php echo esc($model_info->ppda_registration_no ?: 'N/A'); ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">URA TIN Number:</td>
                                <td><strong><?php echo esc($model_info->tin_number ?: 'N/A'); ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Bank Name:</td>
                                <td><?php echo esc($model_info->bank_name ?: 'Stanbic Bank Uganda'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Account Number:</td>
                                <td><?php echo esc($model_info->bank_account_no ?: 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Credit / Payment Terms:</td>
                                <td><?php echo esc($model_info->payment_terms ?: 'Net 30 Days'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Contacts Directory Section -->
            <div class="card p20 border shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 text-primary"><i data-feather="users" class="icon-18"></i> Supplier Contact Persons (<?php echo count($contacts); ?>)</h5>
                    <?php echo modal_anchor(get_uri("suppliers/contact_modal_form"), "<i data-feather='user-plus' class='icon-16 mr5'></i> Add Contact Person", array("class" => "btn btn-sm btn-outline-primary", "title" => "Add Contact Person", "data-post-supplier_id" => $model_info->id)); ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Contact Name</th>
                                <th>Job Title / Role</th>
                                <th>Email Address</th>
                                <th>Phone Numbers</th>
                                <th>Primary Contact</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($contacts)) { 
                                foreach ($contacts as $c) { ?>
                                <tr>
                                    <td>
                                        <strong><?php echo esc($c->first_name . " " . $c->last_name); ?></strong>
                                    </td>
                                    <td><?php echo esc($c->job_title ?: 'Representative'); ?></td>
                                    <td><a href="mailto:<?php echo esc($c->email); ?>"><i data-feather="mail" class="icon-14"></i> <?php echo esc($c->email); ?></a></td>
                                    <td>
                                        <div><i data-feather="phone" class="icon-14"></i> <?php echo esc($c->phone ?: 'N/A'); ?></div>
                                        <?php if ($c->alternative_phone) { ?>
                                            <small class="text-muted"><?php echo esc($c->alternative_phone); ?></small>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($c->is_primary_contact) { ?>
                                            <span class="badge bg-primary">PRIMARY CONTACT</span>
                                        <?php } else { ?>
                                            <span class="badge bg-secondary">Secondary</span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo modal_anchor(get_uri("suppliers/contact_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit btn btn-sm btn-outline-primary me-1", "title" => "Edit Contact", "data-post-id" => $c->id, "data-post-supplier_id" => $model_info->id)); ?>
                                        <?php echo js_anchor("<i data-feather='trash-2' class='icon-16'></i>", array("title" => "Delete Contact", "class" => "delete btn btn-sm btn-outline-danger", "data-id" => $c->id, "data-action-url" => get_uri("suppliers/delete_contact"), "data-action" => "delete-confirmation")); ?>
                                    </td>
                                </tr>
                            <?php } 
                            } else { ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No contact persons registered for this supplier yet. Click "Add Contact Person" above to register contacts.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
