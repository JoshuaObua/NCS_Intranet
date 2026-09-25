<?php echo form_open(get_uri("accounting/save_vote_clearance"), array("id" => "vote-clearance-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="alert alert-info">
        <strong>Requisition Ref:</strong> <?php echo $model_info->requisition_ref; ?><br>
        <strong>Department:</strong> <?php echo $model_info->requesting_department; ?><br>
        <strong>Vote-Head:</strong> <code><?php echo $model_info->vote_head_code; ?></code> - <?php echo $model_info->vote_head_title; ?><br>
        <strong>Requested Sum:</strong> UGX <?php echo number_format((float) ($model_info->requested_amount ?? 0), 2); ?><br>
        <strong>Available Budget:</strong> UGX <?php echo number_format((float) ($model_info->available_budget ?? 0), 2); ?>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="clearance_status" class="col-md-3">Financial Decision</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "clearance_status",
                    array(
                        "PASSED" => "PASSED (Budget Available & Committed)",
                        "HELD" => "HELD (Insufficient Quarter Funds)",
                        "REJECTED" => "REJECTED (Non-Compliant Vote)"
                    ),
                    $model_info->clearance_status,
                    "class='form-control select2' id='clearance_status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="remarks" class="col-md-3">Clearance Remarks</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "remarks",
                    "name" => "remarks",
                    "value" => $model_info->remarks,
                    "class" => "form-control",
                    "placeholder" => "Enter vote-head clearance conditions, PFMA 2015 balance notes...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Submit Vote Clearance</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#vote-clearance-form").appForm({
            onSuccess: function (result) {
                $("#accounting-vote-clearance-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
