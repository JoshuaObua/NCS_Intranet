<?php echo form_open(get_uri("internal_audit/save_discrepancy"), array("id" => "discrepancy-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="discrepancy_code" class="col-md-3">Discrepancy Code</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "discrepancy_code",
                    "name" => "discrepancy_code",
                    "value" => $model_info->discrepancy_code ?: "AUD-DISC-2026-00" . rand(4, 9),
                    "class" => "form-control",
                    "placeholder" => "e.g. AUD-DISC-2026-001",
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
            <label for="entity_type" class="col-md-3">Audit Scope / Entity</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "entity_type",
                    array(
                        "ASSET" => "ASSET (Fixed Assets & Property Valuation)",
                        "FINANCIAL_VOUCHER" => "FINANCIAL VOUCHER (Journal Voucher / Ledger)",
                        "VOTE_HEAD" => "VOTE HEAD (Form 5 Procurement Commitment)",
                        "INVENTORY" => "INVENTORY (Stores Stock Take & Requisition)"
                    ),
                    $model_info->entity_type ?: "ASSET",
                    "class='form-control select2' id='entity_type'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="entity_ref" class="col-md-3">Target Reference Number</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "entity_ref",
                    "name" => "entity_ref",
                    "value" => $model_info->entity_ref,
                    "class" => "form-control",
                    "placeholder" => "e.g. M1007058 / FORM5-2026-089",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="title" class="col-md-3">Finding Title</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "title",
                    "name" => "title",
                    "value" => $model_info->title,
                    "class" => "form-control",
                    "placeholder" => "Brief Title of Audit Finding / Control Failure",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="severity" class="col-md-3">Severity Level</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "severity",
                    array(
                        "LOW" => "LOW (Minor Documentation Note)",
                        "MEDIUM" => "MEDIUM (Procedural Control Gap)",
                        "HIGH" => "HIGH (Financial Exposure Risk)",
                        "CRITICAL" => "CRITICAL (Statutory Non-Compliance / Fraud Risk)"
                    ),
                    $model_info->severity ?: "MEDIUM",
                    "class='form-control select2' id='severity'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="financial_impact_ugx" class="col-md-3">Financial Impact (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "financial_impact_ugx",
                    "name" => "financial_impact_ugx",
                    "value" => $model_info->financial_impact_ugx ?: "0.00",
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
            <label for="status" class="col-md-3">Discrepancy Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "status",
                    array(
                        "OPEN" => "OPEN (Under Investigation)",
                        "UNDER_REVIEW" => "UNDER REVIEW (Awaiting Accountant Explanation)",
                        "RESOLVED" => "RESOLVED (Remediation Confirmed)",
                        "ESCALATED" => "ESCALATED TO GENERAL SECRETARY"
                    ),
                    $model_info->status ?: "OPEN",
                    "class='form-control select2' id='status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="description" class="col-md-3">Detailed Audit Finding</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "description",
                    "name" => "description",
                    "value" => $model_info->description,
                    "class" => "form-control",
                    "placeholder" => "Detailed audit observation, risk criteria, and cause...",
                    "rows" => 3,
                    "data-rule-required" => true
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="management_response" class="col-md-3">Management Response</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "management_response",
                    "name" => "management_response",
                    "value" => $model_info->management_response,
                    "class" => "form-control",
                    "placeholder" => "Management action plan / explanation from responsible officer...",
                    "rows" => 2
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Discrepancy</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#discrepancy-form").appForm({
            onSuccess: function (result) {
                $("#discrepancies-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
