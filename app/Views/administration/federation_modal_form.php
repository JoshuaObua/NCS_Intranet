<?php echo form_open(get_uri("administration/save_federation"), array("id" => "federation-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="code" class="col-md-3">Federation Code</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "code",
                    "name" => "code",
                    "value" => $model_info->code,
                    "class" => "form-control",
                    "placeholder" => "e.g. FUFA, UAF, UNF",
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
            <label for="name" class="col-md-3">Full Federation Name</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "name",
                    "name" => "name",
                    "value" => $model_info->name,
                    "class" => "form-control",
                    "placeholder" => "e.g. Federation of Uganda Football Associations",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="sport_category" class="col-md-3">Sport Category</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "sport_category",
                    array(
                        "Category A (High Impact)" => "Category A (High Impact / Olympic Priority)",
                        "Category B (National Development)" => "Category B (National Development)",
                        "Category C (Emerging)" => "Category C (Emerging Sports)"
                    ),
                    $model_info->sport_category,
                    "class='form-control select2' id='sport_category'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="governance_status" class="col-md-3">Governance Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "governance_status",
                    array(
                        "Fully Compliant" => "Fully Compliant",
                        "Conditional Recognition" => "Conditional Recognition",
                        "Notice of Warning" => "Notice of Warning",
                        "Suspended" => "Suspended"
                    ),
                    $model_info->governance_status,
                    "class='form-control select2' id='governance_status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="president_name" class="col-md-3">President Name</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "president_name",
                    "name" => "president_name",
                    "value" => $model_info->president_name,
                    "class" => "form-control",
                    "placeholder" => "e.g. Eng. Moses Magogo"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="general_secretary_name" class="col-md-3">General Secretary Name</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "general_secretary_name",
                    "name" => "general_secretary_name",
                    "value" => $model_info->general_secretary_name,
                    "class" => "form-control",
                    "placeholder" => "e.g. Edgar Watson"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="annual_grant_allocation" class="col-md-3">Annual Grant Allocation (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "annual_grant_allocation",
                    "name" => "annual_grant_allocation",
                    "value" => number_format($model_info->annual_grant_allocation, 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="disbursed_ytd" class="col-md-3">Disbursed YTD (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "disbursed_ytd",
                    "name" => "disbursed_ytd",
                    "value" => number_format($model_info->disbursed_ytd, 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="international_affiliation" class="col-md-3">International Affiliation Body</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "international_affiliation",
                    "name" => "international_affiliation",
                    "value" => $model_info->international_affiliation,
                    "class" => "form-control",
                    "placeholder" => "e.g. FIFA / CAF, World Athletics"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="compliance_score" class="col-md-3">Compliance Score (%)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "compliance_score",
                    "name" => "compliance_score",
                    "value" => $model_info->compliance_score ?: 85,
                    "class" => "form-control",
                    "type" => "number",
                    "min" => "0",
                    "max" => "100"
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Federation Record</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#federation-form").appForm({
            onSuccess: function (result) {
                $("#admin-federations-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
