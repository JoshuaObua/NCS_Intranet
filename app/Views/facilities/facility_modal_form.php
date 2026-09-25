<?php echo form_open(get_uri("facilities/save_facility"), array("id" => "facility-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="facility_code" class="col-md-3">Facility Code</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "facility_code",
                    "name" => "facility_code",
                    "value" => $model_info->facility_code,
                    "class" => "form-control",
                    "placeholder" => "e.g. FAC-LUG-01",
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
            <label for="title" class="col-md-3">Facility Title</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "title",
                    "name" => "title",
                    "value" => $model_info->title,
                    "class" => "form-control",
                    "placeholder" => "e.g. Lugogo National Indoor Arena",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="category" class="col-md-3">Category</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "category",
                    array(
                        "Indoor Arena" => "Indoor Arena",
                        "Stadium & Turf" => "Stadium & Turf",
                        "Tennis & Racquet" => "Tennis & Racquet",
                        "Specialized Turf" => "Specialized Turf",
                        "Hostels & Lodging" => "Hostels & Lodging",
                        "Regional Hub" => "Regional Hub",
                        "High Altitude Hub" => "High Altitude Hub"
                    ),
                    $model_info->category,
                    "class='form-control select2' id='category'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="location" class="col-md-3">Location / Zone</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "location",
                    "name" => "location",
                    "value" => $model_info->location ?: "Lugogo Sports Complex",
                    "class" => "form-control",
                    "placeholder" => "e.g. Lugogo Sports Complex, Hoima, Kapchorwa"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="capacity" class="col-md-3">Seating / Occupancy Capacity</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "capacity",
                    "name" => "capacity",
                    "value" => $model_info->capacity ?: 1000,
                    "class" => "form-control",
                    "type" => "number"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="ntr_rate_per_day" class="col-md-3">NTR Commercial Rate / Day (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "ntr_rate_per_day",
                    "name" => "ntr_rate_per_day",
                    "value" => number_format((float) ($model_info->ntr_rate_per_day ?? 0), 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="caution_deposit_rate" class="col-md-3">Caution Security Deposit (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "caution_deposit_rate",
                    "name" => "caution_deposit_rate",
                    "value" => number_format((float) ($model_info->caution_deposit_rate ?? 0), 2),
                    "class" => "form-control",
                    "placeholder" => "0.00"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="status" class="col-md-3">Status</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "status",
                    array(
                        "Operational" => "Operational",
                        "Under Renovation" => "Under Renovation",
                        "Maintenance Hold" => "Maintenance Hold"
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
            <label for="description" class="col-md-3">Description & Facilities Specs</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "description",
                    "name" => "description",
                    "value" => $model_info->description,
                    "class" => "form-control",
                    "placeholder" => "Enter specifications, lighting, turf type, amenities...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Facility Record</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#facility-form").appForm({
            onSuccess: function (result) {
                $("#facilities-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
