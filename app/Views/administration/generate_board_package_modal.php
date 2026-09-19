<?php echo form_open(get_uri("administration/save_board_package"), array("id" => "board-package-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="package_ref" class="col-md-3">Package Reference Code</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "package_ref",
                    "name" => "package_ref",
                    "value" => $model_info->package_ref ?: "BDP-2026-Q1-05",
                    "class" => "form-control",
                    "placeholder" => "e.g. BDP-2026-Q1-01",
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="title" class="col-md-3">Policy Paper Title</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "title",
                    "name" => "title",
                    "value" => $model_info->title,
                    "class" => "form-control",
                    "placeholder" => "Enter comprehensive policy or memorandum title",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="board_quarter" class="col-md-3">Board Quarter / Sitting</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "board_quarter",
                    array(
                        "FY 2026/27 Q1" => "FY 2026/27 Quarter 1 Sitting",
                        "FY 2026/27 Q2" => "FY 2026/27 Quarter 2 Sitting",
                        "FY 2026/27 Q3" => "FY 2026/27 Quarter 3 Sitting",
                        "FY 2026/27 Q4" => "FY 2026/27 Quarter 4 Sitting",
                        "Special Executive Sitting" => "Special Executive Sitting"
                    ),
                    $model_info->board_quarter,
                    "class='form-control select2' id='board_quarter'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="category" class="col-md-3">Category</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "category",
                    array(
                        "Cabinet Policy Paper" => "Cabinet Policy Paper",
                        "Ministerial Brief" => "Ministerial Brief",
                        "Quarterly Performance Report" => "Quarterly Performance Report",
                        "Statutory Valuation Return" => "Statutory Valuation Return",
                        "Emergency Capital Request" => "Emergency Capital Request"
                    ),
                    $model_info->category,
                    "class='form-control select2' id='category'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="lead_author_name" class="col-md-3">Lead Author / Sponsor</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "lead_author_name",
                    "name" => "lead_author_name",
                    "value" => $model_info->lead_author_name ?: "Dr. Patrick Ogwel (GS)",
                    "class" => "form-control"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="security_level" class="col-md-3">Security Level</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "security_level",
                    array(
                        "Public" => "Public",
                        "Internal Executive" => "Internal Executive",
                        "Strictly Confidential" => "Strictly Confidential",
                        "Cabinet Eyes Only" => "Cabinet Eyes Only"
                    ),
                    $model_info->security_level,
                    "class='form-control select2' id='security_level'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="status" class="col-md-3">Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "status",
                    array(
                        "Draft in Prep" => "Draft in Prep",
                        "Submitted to GS" => "Submitted to General Secretary",
                        "Approved for Board" => "Approved for Board Presentation",
                        "Presented to Ministry" => "Presented to Ministry"
                    ),
                    $model_info->status,
                    "class='form-control select2' id='status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="target_submission_date" class="col-md-3">Target Submission Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "target_submission_date",
                    "name" => "target_submission_date",
                    "value" => $model_info->target_submission_date ?: date("Y-m-d"),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="executive_summary" class="col-md-3">Executive Summary</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "executive_summary",
                    "name" => "executive_summary",
                    "value" => $model_info->executive_summary,
                    "class" => "form-control",
                    "placeholder" => "Provide brief high-level context, statutory reference, and key decision sought...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Policy Brief Package</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#board-package-form").appForm({
            onSuccess: function (result) {
                $("#admin-board-packages-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
