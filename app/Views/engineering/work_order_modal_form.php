<?php echo form_open(get_uri("engineering/save_work_order"), array("id" => "engineering-work-order-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="title">Title / Subject <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "title", "name" => "title", "value" => $model_info->title, "class" => "form-control", "placeholder" => "e.g. Lugogo Arena Roof Leak Repair", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="category">Category <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <select name="category" id="category" class="form-control select2" required>
                        <option value="CIVIL" <?php echo $model_info->category === "CIVIL" ? "selected" : ""; ?>>Civil & Structural</option>
                        <option value="ELECTRICAL" <?php echo $model_info->category === "ELECTRICAL" ? "selected" : ""; ?>>Electrical & Power Plant</option>
                        <option value="MECHANICAL" <?php echo $model_info->category === "MECHANICAL" ? "selected" : ""; ?>>Mechanical & HVAC</option>
                        <option value="PLUMBING" <?php echo $model_info->category === "PLUMBING" ? "selected" : ""; ?>>Plumbing & Water Systems</option>
                        <option value="TURF_GROUNDS" <?php echo $model_info->category === "TURF_GROUNDS" ? "selected" : ""; ?>>Sports Turf & Grounds</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="priority">Priority</label>
                <div class="col-md-9">
                    <select name="priority" id="priority" class="form-control select2">
                        <option value="LOW" <?php echo $model_info->priority === "LOW" ? "selected" : ""; ?>>Low</option>
                        <option value="MEDIUM" <?php echo $model_info->priority === "MEDIUM" || !$model_info->priority ? "selected" : ""; ?>>Medium</option>
                        <option value="HIGH" <?php echo $model_info->priority === "HIGH" ? "selected" : ""; ?>>High</option>
                        <option value="EMERGENCY" <?php echo $model_info->priority === "EMERGENCY" ? "selected" : ""; ?>>EMERGENCY</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="facility_location">Facility / Venue <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "facility_location", "name" => "facility_location", "value" => $model_info->facility_location, "class" => "form-control", "placeholder" => "e.g. Lugogo Main Pitch / Indoor Arena", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="description">Detailed Scope <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "description", "name" => "description", "value" => $model_info->description, "class" => "form-control", "rows" => 3, "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="assigned_to">Assign to Officer</label>
                <div class="col-md-9">
                    <?php echo form_dropdown("assigned_to", $team_members_dropdown, $model_info->assigned_to, "id='assigned_to' class='select2'"); ?>
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
                <label class="col-md-3" for="status">Work Status</label>
                <div class="col-md-9">
                    <select name="status" id="status" class="form-control select2">
                        <option value="PENDING" <?php echo $model_info->status === "PENDING" ? "selected" : ""; ?>>Pending</option>
                        <option value="IN_PROGRESS" <?php echo $model_info->status === "IN_PROGRESS" ? "selected" : ""; ?>>In Progress</option>
                        <option value="INSPECTED" <?php echo $model_info->status === "INSPECTED" ? "selected" : ""; ?>>Inspected</option>
                        <option value="COMPLETED" <?php echo $model_info->status === "COMPLETED" ? "selected" : ""; ?>>Completed</option>
                        <option value="CLOSED" <?php echo $model_info->status === "CLOSED" ? "selected" : ""; ?>>Closed</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="completion_date">Target Completion Date</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "completion_date", "name" => "completion_date", "value" => $model_info->completion_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
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
        $("#category, #priority, #assigned_to, #status").select2();
        setDatePicker("#completion_date");

        $("#engineering-work-order-form").appForm({
            onSuccess: function (result) {
                $("#engineering-work-orders-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
