<?php echo form_open(get_uri("facilities/save_inspection"), array("id" => "inspection-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="booking_id" class="col-md-3">Venue Booking</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "booking_id",
                    $bookings_dropdown,
                    $model_info->booking_id,
                    "class='form-control select2' id='booking_id'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="inspection_type" class="col-md-3">Inspection Type</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "inspection_type",
                    array(
                        "Pre-Event Safety Check" => "Pre-Event Safety Check (Before Event)",
                        "Post-Event Damage Audit" => "Post-Event Damage Audit (After Event)"
                    ),
                    $model_info->inspection_type,
                    "class='form-control select2' id='inspection_type'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="inspector_name" class="col-md-3">Inspector Name</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "inspector_name",
                    "name" => "inspector_name",
                    "value" => $model_info->inspector_name ?: "Senior Facilities Inspector",
                    "class" => "form-control",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="safety_cleared" class="col-md-3">Safety Clearance</label>
            <div class="col-md-9">
                <?php
                echo form_checkbox(
                    "safety_cleared",
                    "1",
                    ($model_info->safety_cleared == 1 || !$model_info->id) ? true : false,
                    "id='safety_cleared' class='form-check-input'"
                );
                ?>
                <label for="safety_cleared" class="form-check-label ml5">Structure, Fire Exits, & Barriers Verified Safe</label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="damage_deduction_ugx" class="col-md-3">Damage Deduction (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "damage_deduction_ugx",
                    "name" => "damage_deduction_ugx",
                    "value" => number_format($model_info->damage_deduction_ugx, 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="deposit_refund_status" class="col-md-3">Caution Deposit Refund Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "deposit_refund_status",
                    array(
                        "Pending Post-Event Audit" => "Pending Post-Event Audit",
                        "Cleared for Refund" => "Cleared for Full Refund",
                        "Deduction Applied" => "Deduction Applied (Damage Reported)"
                    ),
                    $model_info->deposit_refund_status,
                    "class='form-control select2' id='deposit_refund_status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="remarks" class="col-md-3">Inspection Remarks</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "remarks",
                    "name" => "remarks",
                    "value" => $model_info->remarks,
                    "class" => "form-control",
                    "placeholder" => "Note washroom hygiene, seating state, turf condition...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Inspection Checklist</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#inspection-form").appForm({
            onSuccess: function (result) {
                $("#facility-inspections-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
