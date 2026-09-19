<?php echo form_open(get_uri("legal_compliance/save_litigation"), array("id" => "litigation-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="case_number" class="col-md-3">Case Ref Number</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "case_number",
                    "name" => "case_number",
                    "value" => $model_info->case_number ?: "CS-2026-00" . rand(4,9),
                    "class" => "form-control",
                    "placeholder" => "e.g. HCT-00-CV-CS-0142-2025",
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
            <label for="court_forum" class="col-md-3">Court / Forum</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "court_forum",
                    "name" => "court_forum",
                    "value" => $model_info->court_forum ?: "High Court of Uganda (Civil Division)",
                    "class" => "form-control",
                    "placeholder" => "e.g. High Court Civil Division, Court of Appeal, Land Division",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="parties" class="col-md-3">Parties</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "parties",
                    "name" => "parties",
                    "value" => $model_info->parties,
                    "class" => "form-control",
                    "placeholder" => "Plaintiff vs Defendant (e.g. NCS vs Claimant Ltd)",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="counsel_lead" class="col-md-3">Legal Counsel Lead</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "counsel_lead",
                    "name" => "counsel_lead",
                    "value" => $model_info->counsel_lead ?: "Attorney General Chambers / Solicitor General",
                    "class" => "form-control",
                    "placeholder" => "External Firm or Attorney General Assigned State Attorney"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="financial_exposure" class="col-md-3">Exposure Risk (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "financial_exposure",
                    "name" => "financial_exposure",
                    "value" => $model_info->financial_exposure,
                    "class" => "form-control",
                    "placeholder" => "0.00",
                    "type" => "number",
                    "step" => "0.01"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="next_hearing_date" class="col-md-3">Next Hearing Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "next_hearing_date",
                    "name" => "next_hearing_date",
                    "value" => $model_info->next_hearing_date ?: date("Y-m-d", strtotime("+30 days")),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="status" class="col-md-3">Case Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "status",
                    array(
                        "ACTIVE" => "ACTIVE (Pleadings / Submissions)",
                        "INTERLOCUTORY" => "INTERLOCUTORY (Injunction / Motion)",
                        "JUDGMENT_PENDING" => "JUDGMENT PENDING",
                        "CLOSED" => "CLOSED (Settled / Decided)",
                        "APPEAL_FILED" => "APPEAL FILED"
                    ),
                    $model_info->status ?: "ACTIVE",
                    "class='form-control select2' id='status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="summary" class="col-md-3">Case Summary & Strategy</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "summary",
                    "name" => "summary",
                    "value" => $model_info->summary,
                    "class" => "form-control",
                    "placeholder" => "Summary of claim, defense strategy, evidence status...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Court Case</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#litigation-form").appForm({
            onSuccess: function (result) {
                $("#litigation-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
