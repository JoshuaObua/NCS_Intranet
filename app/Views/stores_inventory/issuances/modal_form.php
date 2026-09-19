<?php echo form_open(get_uri("stores_inventory/save_issuance"), array("id" => "issuance-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <div class="form-group">
            <div class="row">
                <label for="item_id" class="col-md-3">Select SKU / Item <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("item_id", $items_dropdown, array(), "class='select2' id='item_id' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="quantity_issued" class="col-md-3">Quantity to Issue <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "quantity_issued",
                        "name" => "quantity_issued",
                        "type" => "number",
                        "value" => 1,
                        "min" => 1,
                        "class" => "form-control",
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required"),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="issued_to_department_id" class="col-md-3">Target Department <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("issued_to_department_id", $departments_dropdown, array(), "class='select2' id='issued_to_department_id' data-rule-required='true' data-msg-required='" . app_lang("field_required") . "'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="issued_to_role_id" class="col-md-3">Target User Role</label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("issued_to_role_id", $roles_dropdown, array(), "class='select2' id='issued_to_role_id'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="issued_to_user_id" class="col-md-3">Recipient Staff</label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("issued_to_user_id", $users_dropdown, array(), "class='select2' id='issued_to_user_id'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="condition_on_issue" class="col-md-3">Condition on Issue</label>
                <div class="col-md-9">
                    <?php
                    echo form_dropdown("condition_on_issue", array(
                        "good" => "Good Condition",
                        "fair" => "Fair / Acceptable",
                        "new"  => "Brand New Sealed",
                    ), array("good"), "class='select2' id='condition_on_issue'");
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="handover_notes" class="col-md-3">Handover Notes</label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "handover_notes",
                        "name" => "handover_notes",
                        "class" => "form-control",
                        "placeholder" => "Store Issue Voucher (SIV) remarks, serial numbers or re-issuance notes...",
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> Issue Stock (SIV)</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#issuance-form").appForm({
            onSuccess: function (result) {
                $("#issuances-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#item_id, #issued_to_department_id, #issued_to_role_id, #issued_to_user_id, #condition_on_issue").select2();
    });
</script>
