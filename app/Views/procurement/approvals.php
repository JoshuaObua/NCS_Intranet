<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="check-square" class="icon-16 mr5"></i> PPDA Form 5 Requisition Approvals Queue</h4>
            <div class="title-button-group">
                <a href="<?php echo get_uri('procurement/fill_form_5'); ?>" class="btn btn-primary"><i data-feather="plus-circle" class="icon-16 mr5"></i> Fill New Form 5</a>
            </div>
        </div>
        <div class="card-body">
            <!-- SUMMARY STAT CARDS -->
            <div class="row mb20">
                <div class="col-md-3">
                    <div class="card p15 border">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-warning p10 rounded text-white mr15">
                                <i data-feather="clock" class="icon-24"></i>
                            </div>
                            <div>
                                <h4 class="mb0 fw-bold"><?php echo $summary->pending_hod ?: 0; ?></h4>
                                <span class="text-muted small">Pending HOD Confirmation</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p15 border">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-info p10 rounded text-white mr15">
                                <i data-feather="file-text" class="icon-24"></i>
                            </div>
                            <div>
                                <h4 class="mb0 fw-bold"><?php echo $summary->pending_vote ?: 0; ?></h4>
                                <span class="text-muted small">Pending Vote Clearance</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p15 border">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-primary p10 rounded text-white mr15">
                                <i data-feather="award" class="icon-24"></i>
                            </div>
                            <div>
                                <h4 class="mb0 fw-bold"><?php echo $summary->pending_gs ?: 0; ?></h4>
                                <span class="text-muted small">Pending GS Approval</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p15 border">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-success p10 rounded text-white mr15">
                                <i data-feather="check-circle" class="icon-24"></i>
                            </div>
                            <div>
                                <h4 class="mb0 fw-bold"><?php echo $summary->approved_count ?: 0; ?></h4>
                                <span class="text-muted small">Approved Form 5s</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table id="procurement-approvals-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#procurement-approvals-table").appTable({
            source: '<?php echo get_uri("procurement/approvals_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Reference No", "class": "w150"},
                {title: "Subject of Procurement"},
                {title: "Type", "class": "w120"},
                {title: "Est. Total Value", "class": "w130 text-right"},
                {title: "Requester", "class": "w150"},
                {title: "Submitted Date", "class": "w120"},
                {title: "Status", "class": "w150 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ]
        });
    });
</script>
