<?php echo form_open(get_uri("legal_compliance/save_trademark"), array("id" => "trademark-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="reg_number" class="col-md-3">URSB Reg Number</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "reg_number",
                    "name" => "reg_number",
                    "value" => $model_info->reg_number ?: "UG/TM/2026/00" . rand(4,9),
                    "class" => "form-control",
                    "placeholder" => "e.g. UG/TM/2026/001",
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="trademark_name" class="col-md-3">Trademark Name / Logo</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "trademark_name",
                    "name" => "trademark_name",
                    "value" => $model_info->trademark_name,
                    "class" => "form-control",
                    "placeholder" => "e.g. National Council of Sports Official Crest",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="class_category" class="col-md-3">URSB Class</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "class_category",
                    "name" => "class_category",
                    "value" => $model_info->class_category ?: "Class 41 (Sports & Sporting Events)",
                    "class" => "form-control",
                    "placeholder" => "e.g. Class 41 (Education & Entertainment), Class 35 (Advertising)"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="registration_date" class="col-md-3">Registration Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "registration_date",
                    "name" => "registration_date",
                    "value" => $model_info->registration_date ?: date("Y-m-d"),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="renewal_date" class="col-md-3">Renewal Due Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "renewal_date",
                    "name" => "renewal_date",
                    "value" => $model_info->renewal_date ?: date("Y-m-d", strtotime("+10 years")),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="status" class="col-md-3">Protection Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "status",
                    array(
                        "REGISTERED" => "REGISTERED (URSB Protected)",
                        "PENDING_GAZETTE" => "PENDING GAZETTE (URSB Examination)",
                        "RENEWAL_DUE" => "RENEWAL DUE",
                        "EXPIRED" => "EXPIRED"
                    ),
                    $model_info->status ?: "REGISTERED",
                    "class='form-control select2' id='status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="notes" class="col-md-3">IP Usage & Notes</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "notes",
                    "name" => "notes",
                    "value" => $model_info->notes,
                    "class" => "form-control",
                    "placeholder" => "Approved licensing terms, commercial brand usage rules...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Trademark</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#trademark-form").appForm({
            onSuccess: function (result) {
                $("#trademarks-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
