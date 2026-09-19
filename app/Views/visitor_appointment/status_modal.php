<?php echo form_open(get_uri("visitor_appointment/save_status"), array("id" => "appointment-status-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <div class="form-group mb15">
            <label for="status" class="control-label"><?php echo app_lang('appointment_status'); ?> *</label>
            <select name="status" id="status_selector" class="form-control select2">
                <option value="approved" <?php echo ($model_info->status === 'approved') ? 'selected' : ''; ?>><?php echo app_lang('approve_appointment'); ?></option>
                <option value="rejected" <?php echo ($model_info->status === 'rejected') ? 'selected' : ''; ?>><?php echo app_lang('reject_appointment'); ?></option>
                <option value="rescheduled" <?php echo ($model_info->status === 'rescheduled') ? 'selected' : ''; ?>><?php echo app_lang('reschedule_appointment'); ?></option>
            </select>
        </div>

        <div id="reschedule-fields" class="<?php echo ($model_info->status !== 'rescheduled') ? 'hide' : ''; ?>">
            <div class="row">
                <div class="col-md-6 form-group mb15">
                    <label for="rescheduled_date" class="control-label"><?php echo app_lang('rescheduled_date'); ?></label>
                    <input type="text" name="rescheduled_date" id="rescheduled_date" value="<?php echo $model_info->rescheduled_date ? $model_info->rescheduled_date : $model_info->appointment_date; ?>" class="form-control date-picker" placeholder="YYYY-MM-DD" />
                </div>
                <div class="col-md-6 form-group mb15">
                    <label for="rescheduled_time" class="control-label"><?php echo app_lang('rescheduled_time'); ?></label>
                    <input type="time" name="rescheduled_time" id="rescheduled_time" value="<?php echo $model_info->rescheduled_time ? $model_info->rescheduled_time : $model_info->appointment_time; ?>" class="form-control" />
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <label for="status_reason" class="control-label"><?php echo app_lang('status_reason'); ?> *</label>
            <textarea name="status_reason" id="status_reason" class="form-control" rows="4" placeholder="Enter reason for approval, rejection, or rescheduling..." data-rule-required="true" data-msg-required="<?php echo app_lang('field_required'); ?>"><?php echo $model_info->status_reason; ?></textarea>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#appointment-status-form").appForm({
            onSuccess: function (result) {
                $("#visitor-appointments-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#appointment-status-form .select2").select2();
        setDatePicker("#rescheduled_date");

        $("#status_selector").change(function () {
            if ($(this).val() === "rescheduled") {
                $("#reschedule-fields").removeClass("hide");
            } else {
                $("#reschedule-fields").addClass("hide");
            }
        });
    });
</script>
