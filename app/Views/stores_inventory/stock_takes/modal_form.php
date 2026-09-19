<?php echo form_open(get_uri("stores_inventory/save_stock_take"), array("id" => "stock-take-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <label for="item_id" class="col-md-3">Select Inventory SKU <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("item_id", $items_dropdown, array(), "class='select2' id='item_id' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="physical_qty" class="col-md-3">Physical Stock Count <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "physical_qty",
                        "name" => "physical_qty",
                        "type" => "number",
                        "class" => "form-control",
                        "placeholder" => "Actual count found during spot check",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="reconciliation_notes" class="col-md-3">Reconciliation Remarks</label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "reconciliation_notes",
                        "name" => "reconciliation_notes",
                        "class" => "form-control",
                        "placeholder" => "Reason for variance between book inventory and physical count...",
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> Submit Stock Reconciliation</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#stock-take-form").appForm({
            onSuccess: function (result) {
                $("#stock-takes-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#item_id").select2();
    });
</script>
