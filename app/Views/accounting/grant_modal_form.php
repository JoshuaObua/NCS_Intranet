<?php echo form_open(get_uri("accounting/save_grant_disbursement"), array("id" => "grant-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="federation_code" class="col-md-3">Federation Code</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "federation_code",
                    "name" => "federation_code",
                    "value" => $model_info->federation_code,
                    "class" => "form-control",
                    "placeholder" => "e.g. FUFA, UAF, UNF",
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
            <label for="federation_name" class="col-md-3">Federation Full Name</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "federation_name",
                    "name" => "federation_name",
                    "value" => $model_info->federation_name,
                    "class" => "form-control",
                    "placeholder" => "e.g. Federation of Uganda Football Associations",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="grant_quarter" class="col-md-3">Grant Quarter / Fiscal Sitting</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "grant_quarter",
                    array(
                        "FY 2026/27 Q1" => "FY 2026/27 Quarter 1",
                        "FY 2026/27 Q2" => "FY 2026/27 Quarter 2",
                        "FY 2026/27 Q3" => "FY 2026/27 Quarter 3",
                        "FY 2026/27 Q4" => "FY 2026/27 Quarter 4"
                    ),
                    $model_info->grant_quarter,
                    "class='form-control select2' id='grant_quarter'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="allocated_amount" class="col-md-3">Annual Allocated Grant (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "allocated_amount",
                    "name" => "allocated_amount",
                    "value" => number_format($model_info->allocated_amount, 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="disbursed_amount" class="col-md-3">Disbursed Amount YTD (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "disbursed_amount",
                    "name" => "disbursed_amount",
                    "value" => number_format($model_info->disbursed_amount, 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="accountability_status" class="col-md-3">Accountability Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "accountability_status",
                    array(
                        "VERIFIED" => "VERIFIED (Accountabilities Approved)",
                        "PENDING_AUDIT" => "PENDING AUDIT (Under Audit Inspection)",
                        "OVERDUE" => "OVERDUE (Subvention Blocked)"
                    ),
                    $model_info->accountability_status,
                    "class='form-control select2' id='accountability_status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="notes" class="col-md-3">Grant Purpose / Notes</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "notes",
                    "name" => "notes",
                    "value" => $model_info->notes,
                    "class" => "form-control",
                    "placeholder" => "e.g. Uganda Cranes AFCON prep subvention cleared...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Subvention Grant Record</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#grant-form").appForm({
            onSuccess: function (result) {
                $("#accounting-grants-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
