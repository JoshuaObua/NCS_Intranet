<?php echo form_open(get_uri("accounting/save_asset_revaluation"), array("id" => "asset-accounting-revaluation-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="alert alert-info">
        <strong>Fixed Asset:</strong> <?php echo $model_info->asset_tag; ?> - <?php echo $model_info->asset_name; ?><br>
        <strong>Category:</strong> <?php echo $model_info->asset_class; ?><br>
        <strong>Historical Cost:</strong> UGX <?php echo number_format($model_info->historical_cost, 2); ?>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="current_valuation" class="col-md-3">Current Valuation (UGX)</label>
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
            <label for="accumulated_depreciation" class="col-md-3">Accumulated Depreciation (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "accumulated_depreciation",
                    "name" => "accumulated_depreciation",
                    "value" => number_format($model_info->accumulated_depreciation, 2),
                    "class" => "form-control",
                    "placeholder" => "Enter accumulated depreciation"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="remarks" class="col-md-3">IPSAS 17 Valuation Notes</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "remarks",
                    "name" => "remarks",
                    "value" => $model_info->remarks,
                    "class" => "form-control",
                    "placeholder" => "State valuation methodology, straight-line rate, or impairment reason...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Revaluation Ledger</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#asset-accounting-revaluation-form").appForm({
            onSuccess: function (result) {
                $("#accounting-assets-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
