<?php echo form_open(get_uri("stores_inventory/save_requisition_approval"), array("id" => "requisition-approval-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <div class="mb15">
            <strong>Req #: </strong> <code><?php echo $model_info->req_number; ?></code><br>
            <strong>Department: </strong> <?php echo $model_info->department_title; ?><br>
            <strong>Requested By: </strong> <?php echo $model_info->requested_by_name; ?><br>
            <strong>Target Office: </strong> <?php echo $model_info->target_office ?: "-"; ?><br>
            <strong>Reason: </strong> <?php echo $model_info->reason; ?>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="status" class="col-md-3">Decision <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("status", array(
                        "approved_hod" => "Endorse / Approve Requisition",
                        "rejected"     => "Reject Requisition",
                    ), array($model_info->status), "class='select2' id='status'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="hod_remarks" class="col-md-3">HOD Remarks</label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "hod_remarks",
                        "name" => "hod_remarks",
                        "value" => $model_info->hod_remarks,
                        "class" => "form-control",
                        "placeholder" => "Enter endorsement notes or reason for rejection...",
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> Save Decision</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#requisition-approval-form").appForm({
            onSuccess: function (result) {
                $("#requisitions-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#status").select2();
    });
</script>
