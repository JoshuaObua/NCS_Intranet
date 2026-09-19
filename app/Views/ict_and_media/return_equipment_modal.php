<?php echo form_open(get_uri("ict_and_media/save_equipment_return"), array("id" => "return-equipment-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="alert alert-info">
            <i data-feather="info" class="icon-16"></i> Returning Equipment Dispatch Ref: <strong><?php echo $model_info->dispatch_ref; ?></strong><br>
            Custodian: <strong><?php echo $model_info->recipient_name; ?> (<?php echo $model_info->target_department_name; ?>)</strong>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="condition_on_return">Condition on Return <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <select name="condition_on_return" id="condition_on_return" class="form-control select2" required>
                        <option value="EXCELLENT">Excellent (Intact & Clean)</option>
                        <option value="GOOD" selected>Good (Normal Wear)</option>
                        <option value="FAIR">Fair (Minor Servicing Needed)</option>
                        <option value="POOR">Poor (Damaged / Needs Repair)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="remarks">Return Remarks / Notes</label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "remarks", "name" => "remarks", "value" => $model_info->remarks, "class" => "form-control", "rows" => 2, "placeholder" => "e.g. Returned with all accessories, batteries recharged")); ?>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-success"><span data-feather="check-circle" class="icon-16"></span> Confirm Check In</button>
</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#condition_on_return").select2();

        $("#return-equipment-form").appForm({
            onSuccess: function (result) {
                $("#ict-issuances-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
