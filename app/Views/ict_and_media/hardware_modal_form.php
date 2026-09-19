<?php echo form_open(get_uri("ict_and_media/save_hardware"), array("id" => "ict-hardware-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="item_name">Equipment Title <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "item_name", "name" => "item_name", "value" => $model_info->item_name, "class" => "form-control", "placeholder" => "e.g. Sony FX6 4K Camera / DJI Drone / Cisco Switch", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="category">Category <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <select name="category" id="category" class="form-control select2" required>
                        <option value="COMPUTING_HARDWARE" <?php echo $model_info->category === "COMPUTING_HARDWARE" || !$model_info->category ? "selected" : ""; ?>>Computing Hardware (Laptops, Servers, Desktops)</option>
                        <option value="CAMERAS_PHOTOGRAPHY" <?php echo $model_info->category === "CAMERAS_PHOTOGRAPHY" ? "selected" : ""; ?>>Cameras & Photography (4K Cameras, Lenses, Lighting)</option>
                        <option value="DRONES_GIMBALS_CRANES" <?php echo $model_info->category === "DRONES_GIMBALS_CRANES" ? "selected" : ""; ?>>Specialized Media (Drones, Gimbals, Jib Cranes)</option>
                        <option value="NETWORKING" <?php echo $model_info->category === "NETWORKING" ? "selected" : ""; ?>>Networking (Switches, Routers, Fiber APs)</option>
                        <option value="SECURITY_CCTV" <?php echo $model_info->category === "SECURITY_CCTV" ? "selected" : ""; ?>>CCTV & Security (NVRs, Cameras, Turnstiles)</option>
                        <option value="MEDIA_AUDIO_VISUAL" <?php echo $model_info->category === "MEDIA_AUDIO_VISUAL" ? "selected" : ""; ?>>Audio-Visual & Broadcast (Mixers, Mics, Encoders)</option>
                        <option value="CONSUMABLES" <?php echo $model_info->category === "CONSUMABLES" ? "selected" : ""; ?>>IT Consumables (Toner, SD Cards, Cables)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="brand_model">Brand & Model</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "brand_model", "name" => "brand_model", "value" => $model_info->brand_model, "class" => "form-control", "placeholder" => "e.g. Sony ILME-FX6V / Cisco 9300")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="serial_number">Serial Number</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "serial_number", "name" => "serial_number", "value" => $model_info->serial_number, "class" => "form-control", "placeholder" => "e.g. SN-SONY-4021")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="purchase_cost">Purchase Cost (UGX)</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "purchase_cost", "name" => "purchase_cost", "value" => $model_info->purchase_cost ?: "0.00", "class" => "form-control", "type" => "number", "step" => "0.01")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="condition_rating">Condition Health Rating</label>
                <div class="col-md-9">
                    <select name="condition_rating" id="condition_rating" class="form-control select2">
                        <option value="EXCELLENT" <?php echo $model_info->condition_rating === "EXCELLENT" || !$model_info->condition_rating ? "selected" : ""; ?>>Excellent</option>
                        <option value="GOOD" <?php echo $model_info->condition_rating === "GOOD" ? "selected" : ""; ?>>Good</option>
                        <option value="FAIR" <?php echo $model_info->condition_rating === "FAIR" ? "selected" : ""; ?>>Fair (Servicing Recommended)</option>
                        <option value="POOR" <?php echo $model_info->condition_rating === "POOR" ? "selected" : ""; ?>>Poor (Needs Repair)</option>
                        <option value="DAMAGED" <?php echo $model_info->condition_rating === "DAMAGED" ? "selected" : ""; ?>>Damaged / Defective</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="location_assigned">Assigned Vault / Location</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "location_assigned", "name" => "location_assigned", "value" => $model_info->location_assigned ?: "ICT Central Store", "class" => "form-control", "placeholder" => "e.g. Media Production Vault / Core Server Room")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="specifications">Technical Specifications</label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "specifications", "name" => "specifications", "value" => $model_info->specifications, "class" => "form-control", "rows" => 2, "placeholder" => "e.g. 4K RAW, 2x 160GB Memory Cards, 24-105mm Lens")); ?>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#category, #condition_rating").select2();

        $("#ict-hardware-form").appForm({
            onSuccess: function (result) {
                $("#ict-hardware-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
