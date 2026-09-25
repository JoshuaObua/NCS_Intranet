<?php echo form_open(get_uri("accounting/save_reconciliation"), array("id" => "reconciliation-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="reconciliation_ref" class="col-md-3">Reconciliation Ref</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "reconciliation_ref",
                    "name" => "reconciliation_ref",
                    "value" => $model_info->reconciliation_ref ?: "REC-2026-09",
                    "class" => "form-control",
                    "placeholder" => "e.g. REC-2026-08",
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
            <label for="bank_account_name" class="col-md-3">Bank Account Name</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "bank_account_name",
                    "name" => "bank_account_name",
                    "value" => $model_info->bank_account_name ?: "NCS TSA Subvention Account (Bank of Uganda)",
                    "class" => "form-control",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="bank_account_number" class="col-md-3">Bank Account Number</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "bank_account_number",
                    "name" => "bank_account_number",
                    "value" => $model_info->bank_account_number ?: "0020810291028",
                    "class" => "form-control"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="statement_date" class="col-md-3">Statement Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "statement_date",
                    "name" => "statement_date",
                    "value" => $model_info->statement_date ?: date("Y-m-d"),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="system_balance" class="col-md-3">System Ledger Balance (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "system_balance",
                    "name" => "system_balance",
                    "value" => number_format((float) ($model_info->system_balance ?? 0), 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="bank_statement_balance" class="col-md-3">Bank Statement Balance (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "bank_statement_balance",
                    "name" => "bank_statement_balance",
                    "value" => number_format((float) ($model_info->bank_statement_balance ?? 0), 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Reconciliation Log</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#reconciliation-form").appForm({
            onSuccess: function (result) {
                $("#accounting-reconciliation-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
