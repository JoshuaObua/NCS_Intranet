<?php echo form_open(get_uri("stores_inventory/save_item"), array("id" => "inventory-item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

        <div class="form-group">
            <div class="row">
                <label for="sku_code" class="col-md-3">SKU Code</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "sku_code",
                        "name" => "sku_code",
                        "value" => $model_info->sku_code,
                        "class" => "form-control",
                        "placeholder" => "Auto-generated if left blank (e.g. SKU-SP-001)",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="item_name" class="col-md-3">Item Name <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "item_name",
                        "name" => "item_name",
                        "value" => $model_info->item_name,
                        "class" => "form-control",
                        "placeholder" => "e.g. FIFA Match Soccer Balls, HP Toner 85A",
                        "autofocus" => true,
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="category" class="col-md-3">Category <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("category", $category_dropdown, array($model_info->category), "class='select2' id='category' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="unit_of_measure" class="col-md-3">Unit of Measure</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "unit_of_measure",
                        "name" => "unit_of_measure",
                        "value" => $model_info->unit_of_measure ?: 'Units',
                        "class" => "form-control",
                        "placeholder" => "Units, Boxes, Pairs, Meters, Litres",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="unit_cost" class="col-md-3">Unit Cost (UGX)</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "unit_cost",
                        "name" => "unit_cost",
                        "value" => $model_info->unit_cost ? $model_info->unit_cost : 0,
                        "type" => "number",
                        "step" => "0.01",
                        "class" => "form-control",
                        "placeholder" => "0.00",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="quantity_on_hand" class="col-md-3">Quantity on Hand</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "quantity_on_hand",
                        "name" => "quantity_on_hand",
                        "value" => $model_info->quantity_on_hand ? $model_info->quantity_on_hand : 0,
                        "type" => "number",
                        "class" => "form-control",
                        "placeholder" => "Current stock balance",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="min_reorder_level" class="col-md-3">Min Safety Threshold</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "min_reorder_level",
                        "name" => "min_reorder_level",
                        "value" => $model_info->min_reorder_level ? $model_info->min_reorder_level : 5,
                        "type" => "number",
                        "class" => "form-control",
                        "placeholder" => "Alert threshold for low stock",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="warehouse_bin_location" class="col-md-3">Bin / Location</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "warehouse_bin_location",
                        "name" => "warehouse_bin_location",
                        "value" => $model_info->warehouse_bin_location ?: 'Rack A-01',
                        "class" => "form-control",
                        "placeholder" => "e.g. Rack A-01, Locker E-05, Bay 2",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="department_id" class="col-md-3">Department</label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("department_id", $departments_dropdown, array($model_info->department_id), "class='select2' id='department_id'");
                    ?>
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

<script type="text/javascript">
    $(document).ready(function () {
        $("#inventory-item-form").appForm({
            onSuccess: function (result) {
                $("#inventory-items-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#category, #department_id").select2();
    });
</script>
