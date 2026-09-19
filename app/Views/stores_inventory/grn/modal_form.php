<?php echo form_open(get_uri("stores_inventory/save_grn"), array("id" => "grn-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <label for="supplier_name" class="col-md-3">Supplier Name <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "supplier_name",
                        "name" => "supplier_name",
                        "class" => "form-control",
                        "placeholder" => "e.g. Mukwano Industries, HP Uganda Ltd",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="po_reference" class="col-md-3">PO / Contract Ref</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "po_reference",
                        "name" => "po_reference",
                        "class" => "form-control",
                        "placeholder" => "e.g. PO-NCS-2026-088",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="received_date" class="col-md-3">Received Date <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "received_date",
                        "name" => "received_date",
                        "value" => date("Y-m-d"),
                        "class" => "form-control datepicker",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="item_id" class="col-md-3">Received Stock SKU</label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("item_id", $items_dropdown, array(), "class='select2' id='item_id'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="quantity_received" class="col-md-3">Quantity Received</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "quantity_received",
                        "name" => "quantity_received",
                        "type" => "number",
                        "value" => 1,
                        "class" => "form-control",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="total_value" class="col-md-3">Total Value (UGX) <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "total_value",
                        "name" => "total_value",
                        "type" => "number",
                        "step" => "0.01",
                        "class" => "form-control",
                        "placeholder" => "0.00",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="delivery_note_ref" class="col-md-3">Delivery Note Ref</label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "delivery_note_ref",
                        "name" => "delivery_note_ref",
                        "class" => "form-control",
                        "placeholder" => "Supplier Delivery Note #",
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="notes" class="col-md-3">Inspection Notes</label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "notes",
                        "name" => "notes",
                        "class" => "form-control",
                        "placeholder" => "Quality & specifications verification details...",
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> Issue GRN</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#grn-form").appForm({
            onSuccess: function (result) {
                $("#grn-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        setDatePicker("#received_date");
        $("#item_id").select2();
    });
</script>
