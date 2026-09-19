<div class="modal-body clearfix p20">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb15 pb10 border-bottom">
                    <h4>
                        <?php echo app_lang('appointment_details'); ?> #<?php echo $model_info->id; ?>
                    </h4>
                    <div>
                        <?php
                        $status_class = "bg-warning";
                        if ($model_info->status === "approved") {
                            $status_class = "bg-success";
                        } else if ($model_info->status === "rejected") {
                            $status_class = "bg-danger";
                        } else if ($model_info->status === "rescheduled") {
                            $status_class = "bg-primary";
                        }
                        ?>
                        <span class="badge <?php echo $status_class; ?> fs-6"><?php echo strtoupper($model_info->status); ?></span>
                    </div>
                </div>

                <div class="row mb15">
                    <div class="col-md-6">
                        <strong><?php echo app_lang('appointment_type'); ?>:</strong> 
                        <span><?php echo $model_info->appointment_type === 'internal' ? app_lang('internal_staff') : app_lang('external_visitor'); ?></span>
                    </div>
                    <div class="col-md-6">
                        <strong><?php echo app_lang('created_by_user'); ?>:</strong> 
                        <span><?php echo esc($model_info->created_by_user); ?></span>
                    </div>
                </div>

                <?php if ($model_info->appointment_type !== 'internal') { ?>
                    <div class="card bg-light p15 mb15">
                        <h5 class="mt0 mb10 text-primary"><i data-feather="user" class="icon-16"></i> Visitor Information</h5>
                        <div class="row">
                            <div class="col-md-4 mb10">
                                <strong>Full Name:</strong><br />
                                <?php echo esc($model_info->first_name . " " . $model_info->last_name . ($model_info->other_name ? " " . $model_info->other_name : "")); ?>
                            </div>
                            <div class="col-md-4 mb10">
                                <strong>ID / Document:</strong><br />
                                <?php echo esc($model_info->id_type ? strtoupper($model_info->id_type) . ": " . $model_info->id_number : "-"); ?>
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
                <?php } ?>

                <div class="card bg-light p15 mb15">
                    <h5 class="mt0 mb10 text-primary"><i data-feather="calendar" class="icon-16"></i> Visit Schedule & Officer</h5>
                    <div class="row">
                        <div class="col-md-6 mb10">
                            <strong><?php echo app_lang('person_to_visit'); ?>:</strong><br />
                            <?php echo esc($model_info->to_user_name); ?> (<?php echo esc($model_info->to_user_email); ?>)
                        </div>
                        <div class="col-md-6 mb10">
                            <strong>Date & Time:</strong><br />
                            <?php echo format_to_date($model_info->appointment_date, false) . " at " . $model_info->appointment_time; ?>
                        </div>
                        <div class="col-md-12">
                            <strong><?php echo app_lang('reason_for_visit'); ?>:</strong><br />
                            <p class="mb0 text-wrap"><?php echo nl2br(esc($model_info->reason)); ?></p>
                        </div>
                    </div>
                </div>

                <?php if ($model_info->status_reason || $model_info->status_by_user) { ?>
                    <div class="card p15 mb15 border-warning">
                        <h5 class="mt0 mb10 text-warning"><i data-feather="info" class="icon-16"></i> Status Action History</h5>
                        <p class="mb5"><strong>Updated By:</strong> <?php echo esc($model_info->status_by_user ? $model_info->status_by_user : "System"); ?></p>
                        <p class="mb0"><strong>Reason / Remarks:</strong> <?php echo nl2br(esc($model_info->status_reason)); ?></p>
                    </div>
                <?php } ?>

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
    <a href="<?php echo get_uri('visitor_appointment/download_pdf/' . $model_info->id); ?>" target="_blank" class="btn btn-default"><span data-feather="download" class="icon-16"></span> <?php echo app_lang('print_pdf'); ?></a>
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
</div>
