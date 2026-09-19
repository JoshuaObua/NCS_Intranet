<?php echo form_open(get_uri("facilities/save_hostel_occupancy"), array("id" => "hostel-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="room_number" class="col-md-3">Room Number</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "room_number",
                    "name" => "room_number",
                    "value" => $model_info->room_number,
                    "class" => "form-control",
                    "placeholder" => "e.g. Room 101, Room 205",
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
            <label for="athlete_name" class="col-md-3">Athlete Name</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "athlete_name",
                    "name" => "athlete_name",
                    "value" => $model_info->athlete_name,
                    "class" => "form-control",
                    "placeholder" => "Enter athlete full name",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="nin_or_passport" class="col-md-3">NIN or Passport No</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "nin_or_passport",
                    "name" => "nin_or_passport",
                    "value" => $model_info->nin_or_passport,
                    "class" => "form-control",
                    "placeholder" => "e.g. CM900128102910"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="federation_name" class="col-md-3">Sports Federation</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "federation_name",
                    "name" => "federation_name",
                    "value" => $model_info->federation_name,
                    "class" => "form-control",
                    "placeholder" => "e.g. Uganda Athletics Federation"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="gender" class="col-md-3">Gender</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "gender",
                    array(
                        "Male" => "Male Squad",
                        "Female" => "Female Squad"
                    ),
                    $model_info->gender,
                    "class='form-control select2' id='gender'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="check_in_date" class="col-md-3">Check-In Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "check_in_date",
                    "name" => "check_in_date",
                    "value" => $model_info->check_in_date ?: date("Y-m-d"),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="check_out_date" class="col-md-3">Check-Out Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "check_out_date",
                    "name" => "check_out_date",
                    "value" => $model_info->check_out_date ?: date("Y-m-d"),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="status" class="col-md-3">Camp Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "status",
                    array(
                        "Active Camp" => "Active Camp (Resident)",
                        "Checked Out" => "Checked Out"
                    ),
                    $model_info->status,
                    "class='form-control select2' id='status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="key_issued" class="col-md-3">Key Issued</label>
            <div class="col-md-9">
                <?php
                echo form_checkbox(
                    "key_issued",
                    "1",
                    ($model_info->key_issued == 1 || !$model_info->id) ? true : false,
                    "id='key_issued' class='form-check-input'"
                );
                ?>
                <label for="key_issued" class="form-check-label ml5">Room Key Issued to Athlete Custodian</label>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Hostel Allocation</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#hostel-form").appForm({
            onSuccess: function (result) {
                $("#hostel-occupancies-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
