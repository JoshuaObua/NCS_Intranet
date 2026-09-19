<?php echo form_open(get_uri("ict_and_media/save_expense"), array("id" => "ict-expense-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="title">Expense Title / Description <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "title", "name" => "title", "value" => $model_info->title, "class" => "form-control", "placeholder" => "e.g. Annual Cloud S3 Storage License / SD Cards Pack", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="category">Category <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <select name="category" id="category" class="form-control select2" required>
                        <option value="SOFTWARE_LICENSE" <?php echo $model_info->category === "SOFTWARE_LICENSE" || !$model_info->category ? "selected" : ""; ?>>Software License & Subscriptions</option>
                        <option value="CLOUD_HOSTING" <?php echo $model_info->category === "CLOUD_HOSTING" ? "selected" : ""; ?>>Cloud Hosting & Encrypted Backup Storage</option>
                        <option value="DOMAIN_SSL" <?php echo $model_info->category === "DOMAIN_SSL" ? "selected" : ""; ?>>Domain, SSL Certificates & DNS</option>
                        <option value="DRONE_MEDIA_ACCESSORY" <?php echo $model_info->category === "DRONE_MEDIA_ACCESSORY" ? "selected" : ""; ?>>Drone & Media Accessories (SD Cards, Batteries)</option>
                        <option value="NETWORK_CABLING" <?php echo $model_info->category === "NETWORK_CABLING" ? "selected" : ""; ?>>Network Fiber & Patch Cabling</option>
                        <option value="PRINTER_TONER" <?php echo $model_info->category === "PRINTER_TONER" ? "selected" : ""; ?>>Printer Toner & Cartridges</option>
                        <option value="HARDWARE_SPARE" <?php echo $model_info->category === "HARDWARE_SPARE" ? "selected" : ""; ?>>Hardware Spare Parts & Replacements</option>
                        <option value="VENDOR_SERVICE" <?php echo $model_info->category === "VENDOR_SERVICE" ? "selected" : ""; ?>>External Vendor Servicing Fee</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="amount_ugx">Amount (UGX) <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "amount_ugx", "name" => "amount_ugx", "value" => $model_info->amount_ugx ?: "0.00", "class" => "form-control", "type" => "number", "step" => "0.01", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="expense_date">Expense Date</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "expense_date", "name" => "expense_date", "value" => $model_info->expense_date ?: date("Y-m-d"), "class" => "form-control datepicker", "autocomplete" => "off")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="vendor_supplier">Vendor / Supplier</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "vendor_supplier", "name" => "vendor_supplier", "value" => $model_info->vendor_supplier, "class" => "form-control", "placeholder" => "e.g. AWS / Kampala Camera World / HP Uganda")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="invoice_receipt_no">Invoice / Receipt No</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "invoice_receipt_no", "name" => "invoice_receipt_no", "value" => $model_info->invoice_receipt_no, "class" => "form-control", "placeholder" => "e.g. INV-2026-991")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="approved_by">Approved By</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "approved_by", "name" => "approved_by", "value" => $model_info->approved_by ?: "IT Manager", "class" => "form-control", "placeholder" => "e.g. IT Manager / HOD ICT")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="notes">Notes / Justification</label>
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
        $("#category").select2();
        setDatePicker("#expense_date");

        $("#ict-expense-form").appForm({
            onSuccess: function (result) {
                $("#ict-expenses-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
