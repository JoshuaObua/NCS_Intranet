<div class="modal-body clearfix p20">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb15 pb10 border-bottom">
                    <h4>
                        <?php echo app_lang('visitor_records'); ?> #<?php echo $model_info->id; ?>
                    </h4>
                    <div>
                        <?php
                        $status_class = $model_info->status === "checked_in" ? "bg-success" : "bg-dark";
                        ?>
                        <span class="badge <?php echo $status_class; ?> fs-6"><?php echo strtoupper($model_info->status); ?></span>
                    </div>
                </div>

                <div class="card bg-light p15 mb15">
                    <h5 class="mt0 mb10 text-primary"><i data-feather="user" class="icon-16"></i> Visitor Information</h5>
                    <div class="row">
                        <div class="col-md-4 mb10">
                            <strong>Full Name:</strong><br />
                            <?php echo esc($model_info->first_name . " " . $model_info->last_name . ($model_info->other_name ? " " . $model_info->other_name : "")); ?>
                        </div>
                        <div class="col-md-4 mb10">
                            <strong>Nationality / Document:</strong><br />
                            <span class="badge bg-info"><?php echo strtoupper($model_info->nationality); ?></span> <?php echo esc(strtoupper($model_info->id_type) . ": " . $model_info->id_number); ?>
                        </div>
                        <div class="col-md-4 mb10">
                            <strong>Phone / Email:</strong><br />
                            <?php echo esc($model_info->phone ? $model_info->phone : "-"); ?> / <?php echo esc($model_info->email ? $model_info->email : "-"); ?>
                        </div>
                        <div class="col-md-12">
                            <strong>Organization:</strong><br />
                            <?php echo esc($model_info->organization ? $model_info->organization : "-"); ?>
                        </div>
                    </div>
                </div>

                <div class="card bg-light p15 mb15">
                    <h5 class="mt0 mb10 text-primary"><i data-feather="shield" class="icon-16"></i> Gate & Check-In Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb10">
                            <strong>Entry Gate:</strong><br />
                            <?php echo esc($model_info->gate_name); ?>
                        </div>
                        <div class="col-md-6 mb10">
                            <strong>Officer to Visit:</strong><br />
                            <?php echo esc($model_info->to_user_name); ?> (<?php echo esc($model_info->to_user_email); ?>)
                        </div>
                        <div class="col-md-6 mb10">
                            <strong>Time In (Entry):</strong><br />
                            <?php echo format_to_datetime($model_info->time_in); ?> (By <?php echo esc($model_info->recorded_by_user ? $model_info->recorded_by_user : "Security Officer"); ?>)
                        </div>
                        <div class="col-md-6 mb10">
                            <strong>Time Out (Exit):</strong><br />
                            <?php echo $model_info->time_out ? format_to_datetime($model_info->time_out) . " (By " . esc($model_info->checked_out_by_user ? $model_info->checked_out_by_user : "Security Officer") . ")" : "<span class='text-warning'>Still On Premises</span>"; ?>
                        </div>
                        <div class="col-md-12">
                            <strong>Reason for Visit:</strong><br />
                            <p class="mb0 text-wrap"><?php echo nl2br(esc($model_info->reason)); ?></p>
                        </div>
                    </div>
                </div>

                <?php if ($model_info->files) { ?>
                    <div class="mb15">
                        <strong>Attachments:</strong>
                        <div class="mt5">
                            <?php echo view("includes/file_list", array("files" => $model_info->files)); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <a href="<?php echo get_uri('visitor_logbook/download_pdf/' . $model_info->id); ?>" target="_blank" class="btn btn-default"><span data-feather="download" class="icon-16"></span> <?php echo app_lang('print_pdf'); ?></a>
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>
