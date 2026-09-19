<?php echo form_open(get_uri("fixed_assets/save_revaluation"), array("id" => "revaluation-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label class="col-md-3">Asset Number & Tag</label>
            <div class="col-md-9">
                <p class="form-control-plaintext"><strong><?php echo $model_info->asset_number; ?></strong> (Tag: <?php echo $model_info->tag_number; ?>)</p>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label class="col-md-3">Asset Description</label>
            <div class="col-md-9">
                <p class="form-control-plaintext"><?php echo $model_info->asset_description; ?></p>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label class="col-md-3">Initial Cost (FB_COST)</label>
            <div class="col-md-9">
                <p class="form-control-plaintext text-muted">UGX <?php echo number_format($model_info->fb_cost, 2); ?></p>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="adjusted_cost" class="col-md-3">New Revalued Cost (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "adjusted_cost",
                    "name" => "adjusted_cost",
                    "value" => $model_info->adjusted_cost,
                    "class" => "form-control",
                    "type" => "number",
                    "step" => "0.01",
                    "data-rule-required" => true
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="revaluation_notes" class="col-md-3">Revaluation Rationale</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "revaluation_notes",
                    "name" => "revaluation_notes",
                    "value" => "",
                    "class" => "form-control",
                    "placeholder" => "Reason for valuation adjustment (e.g. Government Chief Valuer Report 2026)...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Revaluation</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#revaluation-form").appForm({
            onSuccess: function (result) {
                appNotice.success(result.message);
                window.location.reload();
            }
        });
    });
</script>
