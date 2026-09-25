<?php echo form_open(get_uri("accounting/save_journal"), array("id" => "journal-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="voucher_number" class="col-md-3">Voucher Number</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "voucher_number",
                    "name" => "voucher_number",
                    "value" => $model_info->voucher_number ?: "V-2026-085",
                    "class" => "form-control",
                    "placeholder" => "e.g. V-2026-081",
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
            <label for="posting_date" class="col-md-3">Posting Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "posting_date",
                    "name" => "posting_date",
                    "value" => $model_info->posting_date ?: date("Y-m-d"),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="vote_head_code" class="col-md-3">Vote-Head Code</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "vote_head_code",
                    "name" => "vote_head_code",
                    "value" => $model_info->vote_head_code ?: "221002",
                    "class" => "form-control",
                    "placeholder" => "e.g. 221002, 311101, 142201"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="vote_head_name" class="col-md-3">Vote-Head Title</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "vote_head_name",
                    "name" => "vote_head_name",
                    "value" => $model_info->vote_head_name ?: "Workshops, Seminars & Subventions",
                    "class" => "form-control",
                    "placeholder" => "Enter account title"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="description" class="col-md-3">Journal Description</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "description",
                    "name" => "description",
                    "value" => $model_info->description,
                    "class" => "form-control",
                    "placeholder" => "State purpose of voucher, target payee, or subvention grant...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="debit_amount" class="col-md-3">Debit Amount (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "debit_amount",
                    "name" => "debit_amount",
                    "value" => number_format((float) ($model_info->debit_amount ?? 0), 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="credit_amount" class="col-md-3">Credit Amount (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "credit_amount",
                    "name" => "credit_amount",
                    "value" => number_format((float) ($model_info->credit_amount ?? 0), 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
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
                        "POSTED" => "POSTED (Final Ledger)",
                        "DRAFT" => "DRAFT (Pending CFO Review)",
                        "REVERSED" => "REVERSED"
                    ),
                    $model_info->status,
                    "class='form-control select2' id='status'"
                );
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Post Journal Voucher</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#journal-form").appForm({
            onSuccess: function (result) {
                $("#accounting-ledger-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
