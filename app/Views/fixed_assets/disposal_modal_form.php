<?php echo form_open(get_uri("fixed_assets/request_disposal"), array("id" => "disposal-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label class="col-md-3">Asset Code & Tag</label>
            <div class="col-md-9">
                <p class="form-control-plaintext"><strong><?php echo $model_info->asset_number; ?></strong> (Tag: <code><?php echo $model_info->tag_number; ?></code>)</p>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label class="col-md-3">Description</label>
            <div class="col-md-9">
                <p class="form-control-plaintext"><?php echo $model_info->asset_description; ?></p>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="notes" class="col-md-3">Disposal Reason / Technical Board Assessment</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "notes",
                    "name" => "notes",
                    "value" => "",
                    "class" => "form-control",
                    "placeholder" => "Reason for statutory write-off / disposal recommendation...",
                    "rows" => 3,
                    "data-rule-required" => true
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-danger"><span data-feather="trash-2" class="icon-16"></span> Request Statutory Write-Off</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#disposal-form").appForm({
            onSuccess: function (result) {
                appNotice.success(result.message);
                window.location.reload();
            }
        });
    });
</script>
