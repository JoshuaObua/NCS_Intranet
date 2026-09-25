<?php echo form_open(get_uri("facilities/save_booking"), array("id" => "booking-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="booking_reference" class="col-md-3">Booking Reference</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "booking_reference",
                    "name" => "booking_reference",
                    "value" => $model_info->booking_reference ?: "BKG-2026-005",
                    "class" => "form-control",
                    "placeholder" => "e.g. BKG-2026-001",
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
            <label for="facility_id" class="col-md-3">Facility / Sporting Venue</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "facility_id",
                    $facilities_dropdown,
                    $model_info->facility_id,
                    "class='form-control select2' id='facility_id'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="event_title" class="col-md-3">Event Title / Match Fixture</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "event_title",
                    "name" => "event_title",
                    "value" => $model_info->event_title,
                    "class" => "form-control",
                    "placeholder" => "e.g. National Basketball League Finals",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="client_name" class="col-md-3">Client / Organization Name</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "client_name",
                    "name" => "client_name",
                    "value" => $model_info->client_name,
                    "class" => "form-control",
                    "placeholder" => "e.g. Federation of Uganda Basketball Associations"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="client_type" class="col-md-3">Client Category</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "client_type",
                    array(
                        "Federation" => "National Sports Federation (Subsidized)",
                        "Corporate" => "Corporate / Commercial Entity",
                        "Government" => "Government Agency / Ministry",
                        "Individual" => "Individual / Private Promoter"
                    ),
                    $model_info->client_type,
                    "class='form-control select2' id='client_type'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="start_date" class="col-md-3">Start Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "start_date",
                    "name" => "start_date",
                    "value" => $model_info->start_date ?: date("Y-m-d"),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="end_date" class="col-md-3">End Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "end_date",
                    "name" => "end_date",
                    "value" => $model_info->end_date ?: date("Y-m-d"),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="tariff_category" class="col-md-3">NTR Tariff Scale</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "tariff_category",
                    array(
                        "Subsidized Federation" => "Subsidized Federation Fixture",
                        "Commercial Standard" => "Standard Commercial Event Rate",
                        "Premium Event" => "Premium Concert / Exhibition Rate"
                    ),
                    $model_info->tariff_category,
                    "class='form-control select2' id='tariff_category'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="total_fee_ugx" class="col-md-3">Total Fee (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "total_fee_ugx",
                    "name" => "total_fee_ugx",
                    "value" => number_format((float) ($model_info->total_fee_ugx ?? 0), 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="caution_deposit_ugx" class="col-md-3">Caution Deposit (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "caution_deposit_ugx",
                    "name" => "caution_deposit_ugx",
                    "value" => number_format((float) ($model_info->caution_deposit_ugx ?? 0), 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="payment_status" class="col-md-3">Payment Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "payment_status",
                    array(
                        "PENDING" => "PENDING Payment Verification",
                        "PARTIALLY_PAID" => "PARTIALLY PAID",
                        "FULLY_PAID" => "FULLY PAID (Cleared by Finance)"
                    ),
                    $model_info->payment_status,
                    "class='form-control select2' id='payment_status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="technical_approval" class="col-md-3">AGS-T Technical Clearance</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "technical_approval",
                    array(
                        "APPROVED" => "APPROVED (Calendar Conflict Checked)",
                        "REJECTED" => "REJECTED (Fixtures Conflict)"
                    ),
                    $model_info->technical_approval,
                    "class='form-control select2' id='technical_approval'"
                );
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Venue Booking</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#booking-form").appForm({
            onSuccess: function (result) {
                $("#facility-bookings-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
