<?php echo form_open(get_uri("suppliers/save"), array("id" => "supplier-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group mb-3">
        <label for="company_name" class="col-form-label">Supplier / Company Name <span class="text-danger">*</span></label>
        <input type="text" name="company_name" value="<?php echo $model_info->company_name; ?>" class="form-control" placeholder="e.g. Kampala Engineering Works Ltd" required />
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="supplier_code" class="col-form-label">Supplier Code / Ref</label>
            <input type="text" name="supplier_code" value="<?php echo $model_info->supplier_code; ?>" class="form-control" placeholder="e.g. SUP-2026-001" />
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="category" class="col-form-label">Prequalification Category</label>
            <select name="category" class="form-select">
                <option value="Works & Civil Construction" <?php echo ($model_info->category == "Works & Civil Construction") ? "selected" : ""; ?>>Works & Civil Construction</option>
                <option value="Sports Equipment & Apparel" <?php echo ($model_info->category == "Sports Equipment & Apparel") ? "selected" : ""; ?>>Sports Equipment & Apparel</option>
                <option value="ICT & Broadcast Tech" <?php echo ($model_info->category == "ICT & Broadcast Tech") ? "selected" : ""; ?>>ICT & Broadcast Tech</option>
                <option value="Catering & Accommodation" <?php echo ($model_info->category == "Catering & Accommodation") ? "selected" : ""; ?>>Catering & Accommodation</option>
                <option value="Fleet & Transport Logistics" <?php echo ($model_info->category == "Fleet & Transport Logistics") ? "selected" : ""; ?>>Fleet & Transport Logistics</option>
                <option value="Consulting & Audit Services" <?php echo ($model_info->category == "Consulting & Audit Services") ? "selected" : ""; ?>>Consulting & Audit Services</option>
                <option value="General Supplies" <?php echo ($model_info->category == "General Supplies" || !$model_info->category) ? "selected" : ""; ?>>General Supplies</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="ppda_registration_no" class="col-form-label">PPDA Registration No.</label>
            <input type="text" name="ppda_registration_no" value="<?php echo $model_info->ppda_registration_no; ?>" class="form-control" placeholder="e.g. PRV/2024/09841" />
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="tin_number" class="col-form-label">URA TIN Number</label>
            <input type="text" name="tin_number" value="<?php echo $model_info->tin_number; ?>" class="form-control" placeholder="e.g. 1000984122" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="phone" class="col-form-label">Official Telephone</label>
            <input type="text" name="phone" value="<?php echo $model_info->phone; ?>" class="form-control" placeholder="+256 772 000 000" />
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="email" class="col-form-label">Official Email</label>
            <input type="email" name="email" value="<?php echo $model_info->email; ?>" class="form-control" placeholder="info@supplier.co.ug" />
        </div>
    </div>

    <div class="form-group mb-3">
        <label for="address" class="col-form-label">Physical Business Address</label>
        <textarea name="address" class="form-control" rows="2" placeholder="Plot / Street / Building Address"><?php echo $model_info->address; ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="city" class="col-form-label">City / Location</label>
            <input type="text" name="city" value="<?php echo $model_info->city ?: 'Kampala'; ?>" class="form-control" />
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="country" class="col-form-label">Country</label>
            <input type="text" name="country" value="<?php echo $model_info->country ?: 'Uganda'; ?>" class="form-control" />
        </div>
    </div>

    <hr class="my-3"/>

    <h6 class="text-primary fw-bold"><i data-feather="dollar-sign" class="icon-16"></i> Banking & Payment Terms</h6>

    <div class="row">
        <div class="col-md-4 form-group mb-3">
            <label for="bank_name" class="col-form-label">Bank Name</label>
            <input type="text" name="bank_name" value="<?php echo $model_info->bank_name; ?>" class="form-control" placeholder="Stanbic / DFCU / Absa" />
        </div>
        <div class="col-md-4 form-group mb-3">
            <label for="bank_account_no" class="col-form-label">Bank Account No.</label>
            <input type="text" name="bank_account_no" value="<?php echo $model_info->bank_account_no; ?>" class="form-control" />
        </div>
        <div class="col-md-4 form-group mb-3">
            <label for="payment_terms" class="col-form-label">Credit / Payment Terms</label>
            <input type="text" name="payment_terms" value="<?php echo $model_info->payment_terms ?: 'Net 30 Days'; ?>" class="form-control" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="prequalification_status" class="col-form-label">Pre-qualification Status</label>
            <select name="prequalification_status" class="form-select">
                <option value="PRE_QUALIFIED" <?php echo ($model_info->prequalification_status == "PRE_QUALIFIED" || !$model_info->prequalification_status) ? "selected" : ""; ?>>PRE_QUALIFIED</option>
                <option value="PROVISIONAL" <?php echo ($model_info->prequalification_status == "PROVISIONAL") ? "selected" : ""; ?>>PROVISIONAL</option>
                <option value="PENDING" <?php echo ($model_info->prequalification_status == "PENDING") ? "selected" : ""; ?>>PENDING</option>
                <option value="BLACK_LISTED" <?php echo ($model_info->prequalification_status == "BLACK_LISTED") ? "selected" : ""; ?>>BLACK_LISTED</option>
            </select>
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="rating" class="col-form-label">Vendor Rating Score (1.0 - 5.0)</label>
            <input type="number" step="0.1" max="5.0" min="1.0" name="rating" value="<?php echo $model_info->rating ?: 4.5; ?>" class="form-control" />
        </div>
    </div>

    <div class="form-group mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_blacklisted" id="is_blacklisted" value="1" <?php echo $model_info->is_blacklisted ? "checked" : ""; ?> />
            <label class="form-check-label text-danger fw-bold" for="is_blacklisted">
                Flag as Blacklisted / Debarred Vendor
            </label>
        </div>
    </div>

    <div class="form-group mb-3">
        <label for="blacklist_reason" class="col-form-label">Blacklist / Suspension Reason (If applicable)</label>
        <textarea name="blacklist_reason" class="form-control" rows="2" placeholder="Specify PPDA or internal debarment reference details..."><?php echo $model_info->blacklist_reason; ?></textarea>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><i data-feather="check-circle" class="icon-16"></i> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#supplier-form").appForm({
            onSuccess: function (result) {
                if (result.view === "details") {
                    location.reload();
                } else {
                    $("#suppliers-table").appTable({newData: result.data, dataId: result.id});
                }
            }
        });
        feather.replace();
    });
</script>
