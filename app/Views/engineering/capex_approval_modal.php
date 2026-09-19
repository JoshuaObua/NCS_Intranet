<?php echo form_open(get_uri("engineering/save_capex_approval"), array("id" => "engineering-capex-approval-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="alert alert-info">
        <strong>CapEx Ref:</strong> <?php echo $model_info->capex_ref_no; ?><br>
        <strong>Project Title:</strong> <?php echo $model_info->project_title; ?><br>
        <strong>Est. Budget:</strong> <?php echo to_currency($model_info->estimated_budget); ?>
    </div>

    <div class="form-group mb15">
        <label for="action" class="control-label">Approval Decision Action Stage <span class="text-danger">*</span></label>
        <select name="action" id="action" class="form-control select2" required>
            <?php if ($model_info->status === "PENDING_HOD"): ?>
                <option value="HOD_APPROVE">Stage 1: Senior Engineer (HOD) Technical Endorsement</option>
            <?php elseif ($model_info->status === "HOD_APPROVED"): ?>
                <option value="GS_APPROVE">Stage 2: General Secretary / Accounting Officer Final Sign-off</option>
            <?php endif; ?>
            <option value="REJECT">Reject Requisition</option>
        </select>
    </div>

    <div class="form-group mb15">
        <label for="comments" class="control-label">Endorsement Remarks / Technical Justification</label>
        <textarea name="comments" id="comments" class="form-control" rows="3" placeholder="Enter endorsement notes, budget availability confirmation, or rejection reason..."></textarea>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> Submit Approval Decision</button>
</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#action").select2();

        $("#engineering-capex-approval-form").appForm({
            onSuccess: function (result) {
                $("#engineering-capex-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
