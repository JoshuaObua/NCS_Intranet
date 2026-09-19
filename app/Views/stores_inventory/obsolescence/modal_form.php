<?php echo form_open(get_uri("stores_inventory/save_defect_report"), array("id" => "defect-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <label for="item_id" class="col-md-3">Select Inventory Item <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("item_id", $items_dropdown, array($model_info->id), "class='select2' id='item_id' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="condition" class="col-md-3">Updated Condition <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("condition", array(
                        "defective" => "Defective / Faulty Item",
                        "obsolete"  => "Obsolete / Board of Survey Write-Off",
                        "fair"      => "Damaged / Needs Repair",
                    ), array($model_info->condition), "class='select2' id='condition'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="details" class="col-md-3">Defect Details & Replacement Request</label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "details",
                        "name" => "details",
                        "class" => "form-control",
                        "placeholder" => "Describe defects, cause of damage, or request for replacement parts...",
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
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> Flag Defect / Request Replacement</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#defect-form").appForm({
            onSuccess: function (result) {
                $("#obsolescence-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#item_id, #condition").select2();
    });
</script>
