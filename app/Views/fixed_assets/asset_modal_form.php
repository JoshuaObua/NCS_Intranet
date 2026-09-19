<?php echo form_open(get_uri("fixed_assets/save_asset"), array("id" => "asset-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="asset_number" class="col-md-3">Asset Number</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "asset_number",
                    "name" => "asset_number",
                    "value" => $model_info->asset_number ?: "M100" . rand(7000, 9999),
                    "class" => "form-control",
                    "placeholder" => "e.g. M1007053",
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
            <label for="tag_number" class="col-md-3">Tag Number / Barcode</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "tag_number",
                    "name" => "tag_number",
                    "value" => $model_info->tag_number ?: "NCS-FA-2026-00" . rand(1, 9),
                    "class" => "form-control",
                    "placeholder" => "e.g. NCSUPS014 or UBF 748K",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="asset_description" class="col-md-3">Asset Description</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "asset_description",
                    "name" => "asset_description",
                    "value" => $model_info->asset_description,
                    "class" => "form-control",
                    "placeholder" => "Detailed Description of Fixed Asset",
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required")
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="category_segment3" class="col-md-3">Asset Class / Subcategory</label>
            <div class="col-md-9">
                <?php
                echo form_dropdown(
                    "category_segment3",
                    array(
                        "LIGHT ICT HARDWARE" => "LIGHT ICT HARDWARE (Laptops, Desktops, Printers)",
                        "LIGHT VEHICLES" => "LIGHT VEHICLES (Station Wagons, Double Cabins, Buses)",
                        "ELECTRICAL MACHINERY" => "ELECTRICAL MACHINERY (Generators, Access Control, ACs)",
                        "FURNITURE AND FITTINGS" => "FURNITURE AND FITTINGS (Chairs, Desks, Sofas, Cabinets)",
                        "NON RESIDENTIAL BUILDINGS" => "NON RESIDENTIAL BUILDINGS (Office Floors, Gyms, Pavilions)",
                        "RESIDENTIAL BUILDINGS" => "RESIDENTIAL BUILDINGS (Lugogo Hostels Block)",
                        "LAND" => "LAND (Plots 2-10 Coronation Ave, Hoima, Kapchorwa)",
                        "OFFICE EQUIPMENT" => "OFFICE EQUIPMENT (Shredders, Copiers, Safes)",
                        "OTHER ICT EQUIPMENT" => "OTHER ICT EQUIPMENT (Decoders, Smart TVs, Cameras)",
                        "CYCLES" => "CYCLES (Motorcycles)"
                    ),
                    $model_info->category_segment3 ?: "LIGHT ICT HARDWARE",
                    "class='form-control select2' id='category_segment3'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="category_segment4" class="col-md-3">Detailed Sub-Class</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "category_segment4",
                    "name" => "category_segment4",
                    "value" => $model_info->category_segment4 ?: "General Assets",
                    "class" => "form-control",
                    "placeholder" => "e.g. Laptop, Station Wagon, Generator, Sofa Set"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="fb_cost" class="col-md-3">Initial Cost (FB_COST UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "fb_cost",
                    "name" => "fb_cost",
                    "value" => $model_info->fb_cost,
                    "class" => "form-control",
                    "placeholder" => "0.00",
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
            <label for="adjusted_cost" class="col-md-3">Revalued / Adjusted Cost</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "adjusted_cost",
                    "name" => "adjusted_cost",
                    "value" => $model_info->adjusted_cost ?: $model_info->fb_cost,
                    "class" => "form-control",
                    "placeholder" => "0.00",
                    "type" => "number",
                    "step" => "0.01"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="date_placed_in_service" class="col-md-3">Date Placed in Service</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "date_placed_in_service",
                    "name" => "date_placed_in_service",
                    "value" => $model_info->date_placed_in_service ?: date("Y-m-d"),
                    "class" => "form-control",
                    "type" => "date"
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
                        "ACTIVE" => "ACTIVE (In Operation)",
                        "UNDER_MAINTENANCE" => "UNDER MAINTENANCE",
                        "TRANSFERRED" => "TRANSFERRED TO REGIONAL HUB",
                        "DISPOSED" => "DISPOSED / WRITTEN OFF"
                    ),
                    $model_info->status ?: "ACTIVE",
                    "class='form-control select2' id='status'"
                );
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Asset</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#asset-form").appForm({
            onSuccess: function (result) {
                $("#fixed-assets-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
