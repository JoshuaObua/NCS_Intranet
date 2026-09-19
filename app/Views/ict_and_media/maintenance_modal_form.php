<?php echo form_open(get_uri("ict_and_media/save_maintenance"), array("id" => "ict-maintenance-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="equipment_name">Faulty Equipment Item <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "equipment_name", "name" => "equipment_name", "value" => $model_info->equipment_name, "class" => "form-control", "placeholder" => "e.g. Proaim 22ft Camera Jib Crane / HP LaserJet Printer", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="requester_name">Requester Name <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "requester_name", "name" => "requester_name", "value" => $model_info->requester_name, "class" => "form-control", "placeholder" => "e.g. Francis Mukasa", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="department_name">Department <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "department_name", "name" => "department_name", "value" => $model_info->department_name, "class" => "form-control", "placeholder" => "e.g. Engineering / Finance / PR & Media", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="fault_category">Fault Category</label>
                <div class="col-md-9">
                    <select name="fault_category" id="fault_category" class="form-control select2">
                        <option value="HARDWARE_BREAKAGE" <?php echo $model_info->fault_category === "HARDWARE_BREAKAGE" || !$model_info->fault_category ? "selected" : ""; ?>>Hardware Breakage / Physical Fault</option>
                        <option value="SOFTWARE_OS" <?php echo $model_info->fault_category === "SOFTWARE_OS" ? "selected" : ""; ?>>Software / OS / Firmware Error</option>
                        <option value="NETWORK_CONNECTIVITY" <?php echo $model_info->fault_category === "NETWORK_CONNECTIVITY" ? "selected" : ""; ?>>Network Drop / Switch Port Failure</option>
                        <option value="LENS_CALIBRATION" <?php echo $model_info->fault_category === "LENS_CALIBRATION" ? "selected" : ""; ?>>Camera Lens / Sensor Calibration</option>
                        <option value="DRONE_PROPULSION" <?php echo $model_info->fault_category === "DRONE_PROPULSION" ? "selected" : ""; ?>>Drone Motor / Gimbal Motor Calibration</option>
                        <option value="PRINTER_JAM" <?php echo $model_info->fault_category === "PRINTER_JAM" ? "selected" : ""; ?>>Printer Fuser / Toner / Roller Error</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="priority">Urgency Priority</label>
                <div class="col-md-9">
                    <select name="priority" id="priority" class="form-control select2">
                        <option value="LOW" <?php echo $model_info->priority === "LOW" ? "selected" : ""; ?>>Low (Routine Repair)</option>
                        <option value="MEDIUM" <?php echo $model_info->priority === "MEDIUM" || !$model_info->priority ? "selected" : ""; ?>>Medium (Standard SLA)</option>
                        <option value="HIGH" <?php echo $model_info->priority === "HIGH" ? "selected" : ""; ?>>High (Event Urgent)</option>
                        <option value="EMERGENCY" <?php echo $model_info->priority === "EMERGENCY" ? "selected" : ""; ?>>Emergency (Broadcast / Network Outage)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="service_type">Servicing Route</label>
                <div class="col-md-9">
                    <select name="service_type" id="service_type" class="form-control select2">
                        <option value="INTERNAL_IT" <?php echo $model_info->service_type === "INTERNAL_IT" || !$model_info->service_type ? "selected" : ""; ?>>Internal IT Workshop Repair</option>
                        <option value="EXTERNAL_VENDOR" <?php echo $model_info->service_type === "EXTERNAL_VENDOR" ? "selected" : ""; ?>>External Vendor Service Partner</option>
                        <option value="UNDER_WARRANTY" <?php echo $model_info->service_type === "UNDER_WARRANTY" ? "selected" : ""; ?>>Manufacturer Warranty Claim</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="vendor_name">External Vendor Name (If applicable)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "vendor_name", "name" => "vendor_name", "value" => $model_info->vendor_name, "class" => "form-control", "placeholder" => "e.g. Kampala Electronics Service Ltd")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="estimated_cost">Estimated Cost (UGX)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "estimated_cost", "name" => "estimated_cost", "value" => $model_info->estimated_cost ?: "0.00", "class" => "form-control", "type" => "number", "step" => "0.01")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="status">Repair Status</label>
                <div class="col-md-9">
                    <select name="status" id="status" class="form-control select2">
                        <option value="SUBMITTED" <?php echo $model_info->status === "SUBMITTED" || !$model_info->status ? "selected" : ""; ?>>Submitted (Awaiting Diagnosis)</option>
                        <option value="IN_REPAIR" <?php echo $model_info->status === "IN_REPAIR" ? "selected" : ""; ?>>In Repair / Servicing</option>
                        <option value="COMPLETED" <?php echo $model_info->status === "COMPLETED" ? "selected" : ""; ?>>Completed & Tested</option>
                        <option value="REJECTED" <?php echo $model_info->status === "REJECTED" ? "selected" : ""; ?>>Beyond Repair / Write Off</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="fault_description">Fault Description <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "fault_description", "name" => "fault_description", "value" => $model_info->fault_description, "class" => "form-control", "rows" => 2, "placeholder" => "Detailed description of hardware behavior or failure", "data-rule-required" => true)); ?>
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
        $("#fault_category, #priority, #service_type, #status").select2();

        $("#ict-maintenance-form").appForm({
            onSuccess: function (result) {
                $("#ict-maintenance-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
