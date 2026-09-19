<?php echo form_open(get_uri("administration/save_appraisal_revaluation"), array("id" => "asset-revaluation-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="alert alert-info">
        <strong>Asset Revaluation:</strong> <?php echo $model_info->asset_tag; ?> - <?php echo $model_info->asset_name; ?><br>
        <strong>Location:</strong> <?php echo $model_info->location; ?><br>
        <strong>Historical Cost:</strong> UGX <?php echo number_format($model_info->historical_cost, 2); ?>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="current_valuation" class="col-md-3">New Current Valuation (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "current_valuation",
                    "name" => "current_valuation",
                    "value" => number_format($model_info->current_valuation, 2),
                    "class" => "form-control",
                    "placeholder" => "Enter updated market value"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="condition_rating" class="col-md-3">Condition Rating</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "condition_rating",
                    array(
                        "Excellent" => "Excellent (Like New)",
                        "Good" => "Good (Fully Operational)",
                        "Fair" => "Fair (Needs Routine Maintenance)",
                        "Requires Major Overhaul" => "Requires Major Overhaul",
                        "Obsolete/Impared" => "Obsolete / Impaired"
                    ),
                    $model_info->condition_rating,
                    "class='form-control select2' id='condition_rating'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="remarks" class="col-md-3">Valuer Remarks / Notes</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "remarks",
                    "name" => "remarks",
                    "value" => $model_info->remarks,
                    "class" => "form-control",
                    "placeholder" => "State valuation methodology, land survey notes, or depreciation basis...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Valuation</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#asset-revaluation-form").appForm({
            onSuccess: function (result) {
                $("#admin-appraisals-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
