<?php echo form_open(get_uri("procurement/save_supplier"), array("id" => "procurement-supplier-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="company_name">Company Name <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "company_name", "name" => "company_name", "value" => $model_info->company_name, "class" => "form-control", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="ppda_registration_no">PPDA Reg No <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "ppda_registration_no", "name" => "ppda_registration_no", "value" => $model_info->ppda_registration_no, "class" => "form-control", "placeholder" => "e.g. PRV/2026/01234", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="tin_number">TIN Number <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "tin_number", "name" => "tin_number", "value" => $model_info->tin_number, "class" => "form-control", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="contact_person">Contact Person</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "contact_person", "name" => "contact_person", "value" => $model_info->contact_person, "class" => "form-control")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="contact_email">Contact Email</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "contact_email", "name" => "contact_email", "value" => $model_info->contact_email, "class" => "form-control", "type" => "email")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="contact_phone">Contact Phone</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "contact_phone", "name" => "contact_phone", "value" => $model_info->contact_phone, "class" => "form-control")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="is_blacklisted">PPDA Debarred Status</label>
                <div class="col-md-9">
                    <div class="form-check mt5">
                        <input type="checkbox" id="is_blacklisted" name="is_blacklisted" value="1" class="form-check-input" <?php echo $model_info->is_blacklisted ? "checked" : ""; ?> />
                        <label class="form-check-label text-danger fw-bold" for="is_blacklisted">Blacklisted / Debarred by PPDA</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="blacklist_reason">Blacklist Reason / Notice Ref</label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "blacklist_reason", "name" => "blacklist_reason", "value" => $model_info->blacklist_reason, "class" => "form-control", "rows" => 2, "placeholder" => "e.g. PPDA Circular Ref DB/2026/09 for contract abandonment")); ?>
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
        $("#procurement-supplier-form").appForm({
            onSuccess: function (result) {
                $("#procurement-suppliers-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
