<?php echo form_open(get_uri("procurement/save_plan"), array("id" => "procurement-plan-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="financial_year">Financial Year <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "financial_year", "name" => "financial_year", "value" => $model_info->financial_year ?: "FY 2026/2027", "class" => "form-control", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="procurement_ref_no">Procurement Ref</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "procurement_ref_no", "name" => "procurement_ref_no", "value" => $model_info->procurement_ref_no, "class" => "form-control", "placeholder" => "Auto-generated if blank")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="subject_of_procurement">Subject <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "subject_of_procurement", "name" => "subject_of_procurement", "value" => $model_info->subject_of_procurement, "class" => "form-control", "rows" => 2, "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="procurement_type">Procurement Type <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <select name="procurement_type" id="procurement_type" class="form-control select2" required>
                        <option value="WORKS" <?php echo $model_info->procurement_type === "WORKS" ? "selected" : ""; ?>>Works</option>
                        <option value="SUPPLIES" <?php echo $model_info->procurement_type === "SUPPLIES" ? "selected" : ""; ?>>Supplies</option>
                        <option value="NON_CONSULTANCY_SERVICES" <?php echo $model_info->procurement_type === "NON_CONSULTANCY_SERVICES" ? "selected" : ""; ?>>Non-Consultancy Services</option>
                        <option value="CONSULTANCY_SERVICES" <?php echo $model_info->procurement_type === "CONSULTANCY_SERVICES" ? "selected" : ""; ?>>Consultancy Services</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="procurement_method">Procurement Method <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <select name="procurement_method" id="procurement_method" class="form-control select2" required>
                        <option value="OPEN_DOMESTIC" <?php echo $model_info->procurement_method === "OPEN_DOMESTIC" ? "selected" : ""; ?>>Open Domestic Bidding</option>
                        <option value="RESTRICTED" <?php echo $model_info->procurement_method === "RESTRICTED" ? "selected" : ""; ?>>Restricted Bidding</option>
                        <option value="RFQ" <?php echo $model_info->procurement_method === "RFQ" ? "selected" : ""; ?>>Request for Quotations (RFQ)</option>
                        <option value="DIRECT" <?php echo $model_info->procurement_method === "DIRECT" ? "selected" : ""; ?>>Direct Procurement</option>
                        <option value="MICRO" <?php echo $model_info->procurement_method === "MICRO" ? "selected" : ""; ?>>Micro Procurement</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="estimated_cost">Estimated Cost (UGX) <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "estimated_cost", "name" => "estimated_cost", "value" => $model_info->estimated_cost ?: "0.00", "class" => "form-control", "type" => "number", "step" => "0.01", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="user_department">User Department <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_dropdown("user_department", $departments_dropdown, $model_info->user_department, "id='user_department' class='select2' data-rule-required='true'"); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="planned_invitation_date">Planned Invitation Date</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "planned_invitation_date", "name" => "planned_invitation_date", "value" => $model_info->planned_invitation_date, "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="status">Status</label>
                <div class="col-md-9">
                    <select name="status" id="status" class="form-control select2">
                        <option value="PLANNED" <?php echo $model_info->status === "PLANNED" ? "selected" : ""; ?>>Planned</option>
                        <option value="INITIATED" <?php echo $model_info->status === "INITIATED" ? "selected" : ""; ?>>Initiated</option>
                        <option value="AWARDED" <?php echo $model_info->status === "AWARDED" ? "selected" : ""; ?>>Awarded</option>
                        <option value="CANCELLED" <?php echo $model_info->status === "CANCELLED" ? "selected" : ""; ?>>Cancelled</option>
                    </select>
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
        $("#procurement_type, #procurement_method, #user_department, #status").select2();
        setDatePicker("#planned_invitation_date");

        $("#procurement-plan-form").appForm({
            onSuccess: function (result) {
                $("#procurement-plans-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
