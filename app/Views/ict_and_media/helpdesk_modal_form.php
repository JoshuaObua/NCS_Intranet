<?php echo form_open(get_uri("ict_and_media/save_helpdesk"), array("id" => "ict-helpdesk-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="container-fluid">

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="subject">Issue Subject <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "subject", "name" => "subject", "value" => $model_info->subject, "class" => "form-control", "placeholder" => "e.g. Switch Port #14 Network Drop / Password Lockout", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="requester_name">Requester Name <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "requester_name", "name" => "requester_name", "value" => $model_info->requester_name, "class" => "form-control", "placeholder" => "e.g. Musoke Alex", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="department_name">Department <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "department_name", "name" => "department_name", "value" => $model_info->department_name, "class" => "form-control", "placeholder" => "e.g. Engineering / HR / Administration", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="category">Support Category</label>
                <div class="col-md-9">
                    <select name="category" id="category" class="form-control select2">
                        <option value="NETWORK" <?php echo $model_info->category === "NETWORK" || !$model_info->category ? "selected" : ""; ?>>Network & Wi-Fi Drop</option>
                        <option value="ACCESS_RIGHTS" <?php echo $model_info->category === "ACCESS_RIGHTS" ? "selected" : ""; ?>>User Access Rights / 2FA Reset</option>
                        <option value="EMAIL_INTRANET" <?php echo $model_info->category === "EMAIL_INTRANET" ? "selected" : ""; ?>>Email & Intranet Systems</option>
                        <option value="HARDWARE" <?php echo $model_info->category === "HARDWARE" ? "selected" : ""; ?>>PC / Laptop Hardware Issue</option>
                        <option value="PRINTER" <?php echo $model_info->category === "PRINTER" ? "selected" : ""; ?>>Printer / Scanner Support</option>
                        <option value="MEDIA_SUPPORT" <?php echo $model_info->category === "MEDIA_SUPPORT" ? "selected" : ""; ?>>Media & Broadcast Technical Assistance</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="priority">Priority SLA</label>
                <div class="col-md-9">
                    <select name="priority" id="priority" class="form-control select2">
                        <option value="LOW" <?php echo $model_info->priority === "LOW" ? "selected" : ""; ?>>Low (Standard Ticket)</option>
                        <option value="MEDIUM" <?php echo $model_info->priority === "MEDIUM" || !$model_info->priority ? "selected" : ""; ?>>Medium (4-Hour SLA)</option>
                        <option value="HIGH" <?php echo $model_info->priority === "HIGH" ? "selected" : ""; ?>>High (1-Hour SLA)</option>
                        <option value="URGENT" <?php echo $model_info->priority === "URGENT" ? "selected" : ""; ?>>Urgent (Immediate Service Interruption)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="assigned_to_name">Assigned IT Technician</label>
                <div class="col-md-9">
                    <?php echo form_input(array("id" => "assigned_to_name", "name" => "assigned_to_name", "value" => $model_info->assigned_to_name ?: "IT Systems Admin", "class" => "form-control", "placeholder" => "e.g. IT Systems Admin / Support Tech")); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="status">Ticket Status</label>
                <div class="col-md-9">
                    <select name="status" id="status" class="form-control select2">
                        <option value="OPEN" <?php echo $model_info->status === "OPEN" || !$model_info->status ? "selected" : ""; ?>>Open</option>
                        <option value="IN_PROGRESS" <?php echo $model_info->status === "IN_PROGRESS" ? "selected" : ""; ?>>In Progress</option>
                        <option value="RESOLVED" <?php echo $model_info->status === "RESOLVED" ? "selected" : ""; ?>>Resolved & Closed</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="description">Issue Description <span class="text-danger">*</span></label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "description", "name" => "description", "value" => $model_info->description, "class" => "form-control", "rows" => 2, "placeholder" => "Full description of symptoms or user problem", "data-rule-required" => true)); ?>
                </div>
            </div>
        </div>

        <div class="form-group mb15">
            <div class="row">
                <label class="col-md-3" for="resolution_notes">Resolution Notes</label>
                <div class="col-md-9">
                    <?php echo form_textarea(array("id" => "resolution_notes", "name" => "resolution_notes", "value" => $model_info->resolution_notes, "class" => "form-control", "rows" => 2, "placeholder" => "Steps taken to resolve and verify ticket")); ?>
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
        $("#category, #priority, #status").select2();

        $("#ict-helpdesk-form").appForm({
            onSuccess: function (result) {
                $("#ict-helpdesk-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
