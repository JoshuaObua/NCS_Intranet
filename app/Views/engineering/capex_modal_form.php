<?php echo form_open(get_uri("engineering/save_capex"), array("id" => "engineering-capex-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="project_title">Project Title <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "project_title", "name" => "project_title", "value" => $model_info->project_title, "class" => "form-control", "placeholder" => "e.g. Lugogo Main Arena LED Floodlight Upgrade", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="facility_location">Facility Venue <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "facility_location", "name" => "facility_location", "value" => $model_info->facility_location, "class" => "form-control", "placeholder" => "e.g. Lugogo Stadium Main Pitch", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="estimated_budget">Estimated Budget (UGX) <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "estimated_budget", "name" => "estimated_budget", "value" => $model_info->estimated_budget ?: "0.00", "class" => "form-control", "type" => "number", "step" => "0.01", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="justification">Technical Justification <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "justification", "name" => "justification", "value" => $model_info->justification, "class" => "form-control", "rows" => 4, "placeholder" => "Explain statutory necessity, energy savings, or tournament compliance...", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> Submit CapEx Requisition</button>
</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#engineering-capex-form").appForm({
            onSuccess: function (result) {
                $("#engineering-capex-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
