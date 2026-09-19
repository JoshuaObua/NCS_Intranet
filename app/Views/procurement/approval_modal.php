<?php echo form_open(get_uri("procurement/save_approval"), array("id" => "procurement-approval-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="alert alert-info">
        <strong>Form 5 Ref:</strong> <?php echo $model_info->procurement_ref_no; ?><br>
        <strong>Subject:</strong> <?php echo $model_info->subject_of_procurement; ?><br>
        <strong>Estimated Value:</strong> <?php echo to_currency($model_info->grand_total_estimated_cost); ?>
    </div>

    <div class="form-group mb15">
        <label for="approval_action_select" class="control-label">Select Approval / Forwarding Action <span class="text-danger">*</span></label>
        <select name="action" id="approval_action_select" class="form-control select2" required>
            <option value="FORWARD_SUPERIOR">Option 1: Endorse & Submit to Superior in Current Department</option>
            <option value="FORWARD_DEPT">Option 2: Approve & Forward to Another Department (Inter-Departmental)</option>
            <option value="FINAL_APPROVE">Option 3: Statutory Accounting Officer (GS) Final Sign-off</option>
            <option value="REJECT">Option 4: Reject Requisition</option>
        </select>
    </div>

    <!-- FIELD GROUP FOR FORWARD_SUPERIOR -->
    <div id="group-forward-superior" class="form-group mb15">
        <label for="assigned_approver_id" class="control-label">Target Superior Officer in Department</label>
        <?php echo form_dropdown("assigned_approver_id", $superiors_dropdown, array(), "class='select2 form-control' id='assigned_approver_id'"); ?>
    </div>

    <!-- FIELD GROUP FOR INTER-DEPARTMENTAL FORWARD_DEPT -->
    <div id="group-forward-dept" class="d-none">
        <div class="form-group mb15">
            <label for="target_department_id" class="control-label">Target Department <span class="text-danger">*</span></label>
            <?php echo form_dropdown("target_department_id", $departments_dropdown, array(), "class='select2 form-control' id='target_department_id'"); ?>
        </div>
        <div class="form-group mb15">
            <label for="target_officer_id" class="control-label">Target Officer / Role Rank in Selected Department <span class="text-danger">*</span></label>
            <select name="target_officer_id" id="target_officer_id" class="form-control select2">
                <option value="">Select target department first...</option>
            </select>
        </div>
    </div>

    <div class="form-group mb15">
        <label for="comments" class="control-label">Endorsement Remarks / Approval Notes / Rejection Reason</label>
        <textarea name="comments" id="comments" class="form-control" rows="3" placeholder="Enter instructions, notes, or justification for forwarding..."></textarea>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="send" class="icon-16"></span> Submit Action</button>
</div>
<?php echo form_close(); ?>

<script>
    $(document).ready(function () {
        $("#approval_action_select, #assigned_approver_id, #target_department_id, #target_officer_id").select2();

        function toggleActionFields() {
            let act = $("#approval_action_select").val();
            if (act === "FORWARD_SUPERIOR") {
                $("#group-forward-superior").removeClass("d-none");
                $("#group-forward-dept").addClass("d-none");
            } else if (act === "FORWARD_DEPT") {
                $("#group-forward-superior").addClass("d-none");
                $("#group-forward-dept").removeClass("d-none");
                loadDepartmentOfficers($("#target_department_id").val());
            } else {
                $("#group-forward-superior").addClass("d-none");
                $("#group-forward-dept").addClass("d-none");
            }
        }

        function loadDepartmentOfficers(deptId) {
            if (!deptId) return;
            $.ajax({
                url: "<?php echo get_uri('procurement/get_department_officers'); ?>",
                type: 'POST',
                dataType: 'json',
                data: {department_id: deptId},
                success: function (result) {
                    if (result.success && result.options) {
                        let html = "";
                        $.each(result.options, function (index, item) {
                            html += `<option value="${item.id}">${item.text}</option>`;
                        });
                        $("#target_officer_id").html(html).trigger("change");
                    }
                }
            });
        }

        $("#approval_action_select").change(function () {
            toggleActionFields();
        });

        $("#target_department_id").change(function () {
            loadDepartmentOfficers($(this).val());
        });

        toggleActionFields();

        $("#procurement-approval-form").appForm({
            onSuccess: function (result) {
                $("#procurement-approvals-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>
