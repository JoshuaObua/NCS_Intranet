<?php echo form_open(get_uri("fixed_assets/mark_verified"), array("id" => "audit-spotcheck-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label class="col-md-3">Asset Number & Tag</label>
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
            <label for="verification_status" class="col-md-3">Auditor Action</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "verification_status",
                    array(
                        "VERIFIED" => "VERIFIED OK (Physical Tag & Condition Confirmed)",
                        "DISCREPANCY" => "FLAG DISCREPANCY (Value Mismatch / Damage / Missing Tag)",
                        "UNVERIFIED" => "UNVERIFIED (Pending Field Visit)"
                    ),
                    $model_info->verification_status ?: "VERIFIED",
                    "class='form-control select2' id='verification_status'"
                );
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Audit Verification</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#audit-spotcheck-form").appForm({
            onSuccess: function (result) {
                appNotice.success(result.message);
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
