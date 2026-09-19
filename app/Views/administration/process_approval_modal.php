<?php echo form_open(get_uri("administration/save_approval_decision"), array("id" => "approval-decision-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="alert alert-info">
        <strong>Vetting Requisition:</strong> <?php echo $model_info->reference_no; ?> - <?php echo $model_info->title; ?><br>
        <strong>Originator:</strong> <?php echo $model_info->originator_name; ?> (<?php echo $model_info->originating_department; ?>)<br>
        <strong>Amount:</strong> UGX <?php echo number_format($model_info->amount, 2); ?>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="status" class="col-md-3">Executive Decision</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "status",
                    array(
                        "Approved" => "Approve Requisition",
                        "Forwarded to Board" => "Forward to Board / Ministry",
                        "Rejected" => "Reject Requisition",
                        "Pending GS Vetting" => "Hold for Further Audit"
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
            <label for="vetting_notes" class="col-md-3">General Secretary Vetting Notes</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "vetting_notes",
                    "name" => "vetting_notes",
                    "value" => $model_info->vetting_notes,
                    "class" => "form-control",
                    "placeholder" => "Enter executive remarks, compliance conditions, or financial instructions...",
                    "rows" => 4
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Submit Authorization</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#approval-decision-form").appForm({
            onSuccess: function (result) {
                $("#admin-approvals-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
