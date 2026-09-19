<?php echo form_open(get_uri("engineering/save_technician"), array("id" => "technician-modal-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="full_name">Technician Full Name <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "full_name", "name" => "full_name", "value" => $model_info->full_name, "class" => "form-control", "placeholder" => "e.g. John Okello", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="trade">Trade Discipline <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <select name="trade" id="trade" class="form-control select2" required>
                        <option value="ELECTRICAL" <?php echo $model_info->trade === "ELECTRICAL" || !$model_info->trade ? "selected" : ""; ?>>Electrical & Power Systems</option>
                        <option value="PLUMBING" <?php echo $model_info->trade === "PLUMBING" ? "selected" : ""; ?>>Plumbing, Water & Drainage</option>
                        <option value="CIVIL_MASONRY" <?php echo $model_info->trade === "CIVIL_MASONRY" ? "selected" : ""; ?>>Civil Construction & Masonry</option>
                        <option value="HVAC_REFRIGERATION" <?php echo $model_info->trade === "HVAC_REFRIGERATION" ? "selected" : ""; ?>>HVAC & Refrigeration</option>
                        <option value="TURF_GROUNDS" <?php echo $model_info->trade === "TURF_GROUNDS" ? "selected" : ""; ?>>Sports Turf & Grounds Maintenance</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="phone_number">Phone Contact</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "phone_number", "name" => "phone_number", "value" => $model_info->phone_number, "class" => "form-control", "placeholder" => "e.g. +256 700 123456")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="skill_certification">Skill Certification</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "skill_certification", "name" => "skill_certification", "value" => $model_info->skill_certification, "class" => "form-control", "placeholder" => "e.g. Class A Electrician Permit / National Trade Certificate")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="assigned_station">Stationed Facility <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "assigned_station", "name" => "assigned_station", "value" => $model_info->assigned_station, "class" => "form-control", "placeholder" => "e.g. Lugogo Complex Main Substation", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="duty_status">Duty Shift Status</label>
                <div class="col-md-9">
                    <select name="duty_status" id="duty_status" class="form-control select2">
                        <option value="ON_DUTY" <?php echo $model_info->duty_status === "ON_DUTY" || !$model_info->duty_status ? "selected" : ""; ?>>On Duty (Active Shift)</option>
                        <option value="ON_CALL" <?php echo $model_info->duty_status === "ON_CALL" ? "selected" : ""; ?>>On Call (Standby Emergency)</option>
                        <option value="OFF_DUTY" <?php echo $model_info->duty_status === "OFF_DUTY" ? "selected" : ""; ?>>Off Duty / Leave</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="notes">Roster / Skill Remarks</label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "notes", "name" => "notes", "value" => $model_info->notes, "class" => "form-control", "rows" => 2)); ?>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#trade, #duty_status").select2();

        $("#technician-modal-form").appForm({
            onSuccess: function (result) {
                $("#technicians-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
