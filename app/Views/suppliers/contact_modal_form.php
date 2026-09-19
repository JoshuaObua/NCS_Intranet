<?php echo form_open(get_uri("suppliers/save_contact"), array("id" => "supplier-contact-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group mb-3">
        <label for="supplier_id" class="col-form-label">Supplier Company <span class="text-danger">*</span></label>
        <?php echo form_dropdown("supplier_id", $suppliers_dropdown, array($supplier_id), "class='form-select' required"); ?>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="first_name" class="col-form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" name="first_name" value="<?php echo $model_info->first_name; ?>" class="form-control" placeholder="e.g. Isaac" required />
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="last_name" class="col-form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" name="last_name" value="<?php echo $model_info->last_name; ?>" class="form-control" placeholder="e.g. Okello" required />
        </div>
    </div>

    <div class="form-group mb-3">
        <label for="job_title" class="col-form-label">Job Title / Role</label>
        <input type="text" name="job_title" value="<?php echo $model_info->job_title; ?>" class="form-control" placeholder="e.g. Managing Director / Account Manager" />
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="email" class="col-form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" value="<?php echo $model_info->email; ?>" class="form-control" placeholder="contact@supplier.co.ug" required />
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="phone" class="col-form-label">Mobile Telephone</label>
            <input type="text" name="phone" value="<?php echo $model_info->phone; ?>" class="form-control" placeholder="+256 772 000 000" />
        </div>
    </div>

    <div class="form-group mb-3">
        <label for="alternative_phone" class="col-form-label">Alternative / Landline Phone</label>
        <input type="text" name="alternative_phone" value="<?php echo $model_info->alternative_phone; ?>" class="form-control" placeholder="+256 414 000 000" />
    </div>

    <div class="form-group mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_primary_contact" id="is_primary_contact" value="1" <?php echo $model_info->is_primary_contact ? "checked" : ""; ?> />
            <label class="form-check-label text-primary fw-bold" for="is_primary_contact">
                Set as Primary Contact Person for this Supplier Company
            </label>
        </div>
    </div>

    <div class="form-group mb-3">
        <label for="notes" class="col-form-label">Notes & Remarks</label>
        <textarea name="notes" class="form-control" rows="2" placeholder="Key responsibilities or notes..."><?php echo $model_info->notes; ?></textarea>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><i data-feather="check-circle" class="icon-16"></i> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#supplier-contact-form").appForm({
            onSuccess: function (result) {
                if ($("#supplier-contacts-table").length) {
                    $("#supplier-contacts-table").appTable({newData: result.data, dataId: result.id});
                } else {
                    location.reload();
                }
            }
        });
        feather.replace();
    });
</script>
