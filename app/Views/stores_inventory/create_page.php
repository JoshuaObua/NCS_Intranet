<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="plus-circle" class="icon-16 mr5"></i> <?php echo app_lang("add_new_inventory_record"); ?></h4>
        </div>
        <div class="card-body p30">
            <?php echo form_open(get_uri("stores_inventory/save_item"), array("id" => "inventory-item-page-form", "class" => "general-form", "role" => "form")); ?>

            <div class="row">
                <div class="col-md-6 form-group mb15">
                    <label for="sku_code" class="control-label">SKU Code</label>
                    <?php
                    echo form_input(array(
                        "id" => "sku_code",
                        "name" => "sku_code",
                        "class" => "form-control",
                        "placeholder" => "Auto-generated if blank (e.g. SKU-SP-001)",
                    ));
                    ?>
                </div>
                <div class="col-md-6 form-group mb15">
                    <label for="item_name" class="control-label">Item Name <span class="text-danger">*</span></label>
                    <?php
                    echo form_input(array(
                        "id" => "item_name",
                        "name" => "item_name",
                        "class" => "form-control",
                        "placeholder" => "e.g. FIFA Match Soccer Balls, HP Toner 85A",
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group mb15">
                    <label for="category" class="control-label">Category <span class="text-danger">*</span></label>
                    <?php
                    echo form_dropdown("category", $category_dropdown, array(), "class='select2 form-control' id='category' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'");
                    ?>
                </div>
                <div class="col-md-6 form-group mb15">
                    <label for="unit_of_measure" class="control-label">Unit of Measure</label>
                    <?php
                    echo form_input(array(
                        "id" => "unit_of_measure",
                        "name" => "unit_of_measure",
                        "value" => "Units",
                        "class" => "form-control",
                        "placeholder" => "Units, Boxes, Pairs, Meters, Litres",
                    ));
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group mb15">
                    <label for="unit_cost" class="control-label">Unit Cost (UGX)</label>
                    <?php
                    echo form_input(array(
                        "id" => "unit_cost",
                        "name" => "unit_cost",
                        "value" => "0.00",
                        "type" => "number",
                        "step" => "0.01",
                        "class" => "form-control",
                        "placeholder" => "0.00",
                    ));
                    ?>
                </div>
                <div class="col-md-6 form-group mb15">
                    <label for="quantity_on_hand" class="control-label">Initial Quantity on Hand</label>
                    <?php
                    echo form_input(array(
                        "id" => "quantity_on_hand",
                        "name" => "quantity_on_hand",
                        "value" => "0",
                        "type" => "number",
                        "class" => "form-control",
                        "placeholder" => "Initial stock quantity",
                    ));
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group mb15">
                    <label for="min_reorder_level" class="control-label">Minimum Safety Threshold</label>
                    <?php
                    echo form_input(array(
                        "id" => "min_reorder_level",
                        "name" => "min_reorder_level",
                        "value" => "5",
                        "type" => "number",
                        "class" => "form-control",
                        "placeholder" => "Alert threshold for low stock",
                    ));
                    ?>
                </div>
                <div class="col-md-6 form-group mb15">
                    <label for="warehouse_bin_location" class="control-label">Warehouse Bin / Location</label>
                    <?php
                    echo form_input(array(
                        "id" => "warehouse_bin_location",
                        "name" => "warehouse_bin_location",
                        "value" => "Central Warehouse",
                        "class" => "form-control",
                        "placeholder" => "e.g. Rack A-01, Locker E-05, Bay 2",
                    ));
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group mb15">
                    <label for="department_id" class="control-label">Assigned Department</label>
                    <?php
                    echo form_dropdown("department_id", $departments_dropdown, array(), "class='select2 form-control' id='department_id'");
                    ?>
                </div>
                <div class="col-md-6 form-group mb15">
                    <label for="condition" class="control-label">Initial Item Condition</label>
                    <?php
                    echo form_dropdown("condition", $condition_dropdown, array("good"), "class='select2 form-control' id='condition'");
                    ?>
                </div>
            </div>

            <div class="form-group mt20">
                <a href="<?php echo get_uri('stores_inventory'); ?>" class="btn btn-default"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('cancel'); ?></a>
                <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#inventory-item-page-form").appForm({
            onSuccess: function (result) {
                appSuccessMessenger(result.message);
                window.location.href = "<?php echo get_uri('stores_inventory'); ?>";
            }
        });
        $("#category, #department_id, #condition").select2();
    });
</script>
