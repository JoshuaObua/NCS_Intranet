<?php echo form_open(get_uri("stores_inventory/save_requisition"), array("id" => "requisition-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <label for="department_id" class="col-md-3">Requesting Department <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("department_id", $departments_dropdown, array($model_info->department_id), "class='select2' id='department_id' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="target_office" class="col-md-3">User Office / Room</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "target_office",
                        "name" => "target_office",
                        "class" => "form-control",
                        "placeholder" => "e.g. Office 204, AGS-A Secretariat, Gate 1 Security Booth",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="reason" class="col-md-3">Reason for Requisition <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "reason",
                        "name" => "reason",
                        "class" => "form-control",
                        "placeholder" => "Provide detailed operational justification...",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> Submit Requisition</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#requisition-form").appForm({
            onSuccess: function (result) {
                $("#requisitions-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#department_id").select2();
    });
</script>
