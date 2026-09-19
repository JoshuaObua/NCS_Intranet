<?php echo form_open(get_uri("legal_compliance/save_dispute"), array("id" => "dispute-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="dispute_ref" class="col-md-3">Dispute Ref</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "dispute_ref",
                    "name" => "dispute_ref",
                    "value" => $model_info->dispute_ref ?: "DISP-2026-00" . rand(4,9),
                    "class" => "form-control",
                    "placeholder" => "e.g. DISP-2026-001",
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
            <label for="federation_name" class="col-md-3">Federation / Entity</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "federation_name",
                    "name" => "federation_name",
                    "value" => $model_info->federation_name,
                    "class" => "form-control",
                    "placeholder" => "e.g. Uganda Boxing Federation",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="subject" class="col-md-3">Dispute Subject</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "subject",
                    "name" => "subject",
                    "value" => $model_info->subject,
                    "class" => "form-control",
                    "placeholder" => "Brief Summary of Executive Election / Governance Dispute",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="arbitrator" class="col-md-3">Arbitrator / Panel</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "arbitrator",
                    "name" => "arbitrator",
                    "value" => $model_info->arbitrator,
                    "class" => "form-control",
                    "placeholder" => "e.g. NCS Appeals Committee / Advocate Counsel"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="hearing_date" class="col-md-3">Hearing Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "hearing_date",
                    "name" => "hearing_date",
                    "value" => $model_info->hearing_date ?: date("Y-m-d", strtotime("+7 days")),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="status" class="col-md-3">Dispute Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "status",
                    array(
                        "PENDING_HEARING" => "PENDING HEARING (In Arbitration)",
                        "UNDER_INVESTIGATION" => "UNDER INVESTIGATION (Fact-Finding)",
                        "RESOLVED" => "RESOLVED (Binding Ruling Issued)",
                        "DISMISSED" => "DISMISSED"
                    ),
                    $model_info->status ?: "PENDING_HEARING",
                    "class='form-control select2' id='status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="outcome_ruling" class="col-md-3">Outcome / Ruling</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "outcome_ruling",
                    "name" => "outcome_ruling",
                    "value" => $model_info->outcome_ruling,
                    "class" => "form-control",
                    "placeholder" => "Details of determination, sanctions, or constitution amendments ordered...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Dispute</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#dispute-form").appForm({
            onSuccess: function (result) {
                $("#disputes-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
