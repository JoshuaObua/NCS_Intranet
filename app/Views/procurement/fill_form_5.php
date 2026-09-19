<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="file-text" class="icon-16 mr5"></i> PPDA Procurement Form 5 — Statutory Requisition Wizard</h4>
        </div>
        <div class="card-body p30">
            <?php echo form_open(get_uri("procurement/save_form_5"), array("id" => "procurement-form5-form", "class" => "general-form", "role" => "form")); ?>

            <div class="alert alert-info">
                <strong><i data-feather="info" class="icon-16"></i> Statutory PPDA Regulation 3(1), 13(3), 15(3)</strong><br>
                Form 5 is mandatory for any user department within a public entity (NCS) to initiate a request for approval of procurement.
            </div>

            <!-- AUTO-DETECTED USER DEPARTMENT & BUREAUCRACY RANK INFO BANNER -->
            <div class="card bg-light p15 border mb20">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted text-uppercase fw-bold">Initiating Officer:</small><br>
                        <strong><i data-feather="user" class="icon-14 mr5"></i> <?php echo $user_dept_info->user_name; ?></strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted text-uppercase fw-bold">Auto-Detected Department:</small><br>
                        <strong><i data-feather="grid" class="icon-14 mr5"></i> <?php echo $user_dept_info->department_title; ?> (Code: <?php echo $user_dept_info->department_code; ?>)</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted text-uppercase fw-bold">Bureaucracy Rank & Role:</small><br>
                        <span class="badge bg-primary">Rank <?php echo $user_dept_info->role_rank; ?></span> <strong><?php echo $user_dept_info->role_title; ?></strong>
                    </div>
                </div>
            </div>

            <!-- SECTION 1: PROCUREMENT REFERENCE NUMBER -->
            <h5 class="mb15 border-bottom pb10 text-primary"><i data-feather="hash" class="icon-16"></i> Section 1: Procurement Reference Details</h5>
            <div class="row">
                <div class="col-md-3 form-group mb15">
                    <label for="pde_code" class="control-label">PDE Code</label>
                    <input type="text" id="pde_code" name="pde_code" value="NCS" class="form-control" readonly />
                </div>
                <div class="col-md-3 form-group mb15">
                    <label for="procurement_type" class="control-label">Procurement Type <span class="text-danger">*</span></label>
                    <?php echo form_dropdown("procurement_type", $procurement_type_dropdown, "SUPPLIES", "class='select2 form-control' id='procurement_type' data-rule-required='true'"); ?>
                </div>
                <div class="col-md-3 form-group mb15">
                    <label for="financial_year" class="control-label">Financial Year <span class="text-danger">*</span></label>
                    <input type="text" id="financial_year" name="financial_year" value="2026/2027" class="form-control" placeholder="e.g. 2026/2027" data-rule-required="true" />
                </div>
                <div class="col-md-3 form-group mb15">
                    <label for="sequence_number" class="control-label">Sequence Auto-Ref</label>
                    <input type="text" id="sequence_number" name="sequence_number" value="<?php echo $sequence_number; ?>" class="form-control" readonly />
                </div>
            </div>

            <!-- SECTION 2: CATEGORY OF PROCUREMENT AND BUDGET -->
            <h5 class="mb15 border-bottom pb10 text-primary mt15"><i data-feather="dollar-sign" class="icon-16"></i> Section 2: Category of Procurement & Budget</h5>
            <div class="row">
                <div class="col-md-4 form-group mb15">
                    <label for="budget_category" class="control-label">Budget Category <span class="text-danger">*</span></label>
                    <?php echo form_dropdown("budget_category", $budget_category_dropdown, "RECURRENT_BUDGET", "class='select2 form-control' id='budget_category'"); ?>
                </div>
                <div class="col-md-4 form-group mb15">
                    <label for="recurrent_budget_code" class="control-label">Recurrent Budget Code</label>
                    <input type="text" id="recurrent_budget_code" name="recurrent_budget_code" class="form-control" placeholder="e.g. BUDGET-2026-ENG-001" />
                </div>
                <div class="col-md-4 form-group mb15">
                    <label for="project_title" class="control-label">Associated Project Title</label>
                    <input type="text" id="project_title" name="project_title" class="form-control" placeholder="e.g. Lugogo Arena Floodlight Repair" />
                </div>
            </div>

            <!-- SECTION 3: MULTIYEAR CONTRACTING & RESOURCES -->
            <h5 class="mb15 border-bottom pb10 text-primary mt15"><i data-feather="calendar" class="icon-16"></i> Section 3: Multi-Year Contracting & Required Resources</h5>
            <div class="row">
                <div class="col-md-3 form-group mb15">
                    <label for="is_multiyear" class="control-label">Multi-Year Contract?</label>
                    <div class="form-check mt5">
                        <input type="checkbox" id="is_multiyear" name="is_multiyear" value="1" class="form-check-input" />
                        <label class="form-check-label" for="is_multiyear">Yes, spans multiple financial years</label>
                    </div>
                </div>
                <div class="col-md-3 form-group mb15">
                    <label for="required_ugx_yr1" class="control-label">Required Year 1 (UGX)</label>
                    <input type="number" step="0.01" id="required_ugx_yr1" name="required_ugx_yr1" value="0.00" class="form-control" />
                </div>
                <div class="col-md-3 form-group mb15">
                    <label for="required_ugx_yr2" class="control-label">Required Year 2 (UGX)</label>
                    <input type="number" step="0.01" id="required_ugx_yr2" name="required_ugx_yr2" value="0.00" class="form-control" />
                </div>
                <div class="col-md-3 form-group mb15">
                    <label for="required_ugx_yr3" class="control-label">Required Year 3 (UGX)</label>
                    <input type="number" step="0.01" id="required_ugx_yr3" name="required_ugx_yr3" value="0.00" class="form-control" />
                </div>
            </div>

            <!-- SECTION 4: PARTICULARS OF PROCUREMENT -->
            <h5 class="mb15 border-bottom pb10 text-primary mt15"><i data-feather="clipboard" class="icon-16"></i> Section 4: Particulars of Procurement</h5>
            <div class="row">
                <div class="col-md-12 form-group mb15">
                    <label for="subject_of_procurement" class="control-label">Subject of Procurement <span class="text-danger">*</span></label>
                    <textarea id="subject_of_procurement" name="subject_of_procurement" class="form-control" rows="2" placeholder="Clear summary of items, works or services to be procured" data-rule-required="true"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group mb15">
                    <label for="procurement_plan_ref" class="control-label">Procurement Plan Reference <span class="text-danger">*</span></label>
                    <input type="text" id="procurement_plan_ref" name="procurement_plan_ref" class="form-control" placeholder="e.g. PP-2026-ENG-012" data-rule-required="true" />
                </div>
                <div class="col-md-4 form-group mb15">
                    <label for="location_for_delivery" class="control-label">Target Location for Delivery <span class="text-danger">*</span></label>
                    <input type="text" id="location_for_delivery" name="location_for_delivery" class="form-control" placeholder="e.g. Lugogo National Stadium Warehouse" data-rule-required="true" />
                </div>
                <div class="col-md-4 form-group mb15">
                    <label for="date_required" class="control-label">Required Delivery Date <span class="text-danger">*</span></label>
                    <input type="text" id="date_required" name="date_required" class="form-control datepicker" placeholder="YYYY-MM-DD" autocomplete="off" data-rule-required="true" />
                </div>
            </div>

            <!-- SECTION 5: DETAILS RELATING TO PROCUREMENT (LINE ITEMS TABLE) -->
            <h5 class="mb15 border-bottom pb10 text-primary mt15"><i data-feather="list" class="icon-16"></i> Section 5: Itemized Details & Cost Estimates</h5>
            
            <div class="table-responsive mb15">
                <table class="table table-bordered" id="form5-items-table">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 35%;">Item Description / Specifications <span class="text-danger">*</span></th>
                            <th style="width: 12%;">Qty <span class="text-danger">*</span></th>
                            <th style="width: 13%;">UOM <span class="text-danger">*</span></th>
                            <th style="width: 15%;">Est. Unit Cost (UGX) <span class="text-danger">*</span></th>
                            <th style="width: 15%;">Market Price (UGX)</th>
                            <th style="width: 5%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><input type="text" class="form-control item-desc" placeholder="Technical specs or scope" required /></td>
                            <td><input type="number" step="0.01" class="form-control item-qty" value="1" min="0.01" required /></td>
                            <td><input type="text" class="form-control item-uom" value="Pcs" required /></td>
                            <td><input type="number" step="0.01" class="form-control item-cost" value="0.00" min="0" required /></td>
                            <td><input type="number" step="0.01" class="form-control item-market" value="0.00" min="0" /></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row-btn"><i data-feather="x" class="icon-14"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mb20">
                <div class="col-md-6">
                    <button type="button" id="add-item-row-btn" class="btn btn-default"><i data-feather="plus" class="icon-16 mr5"></i> Add Line Item</button>
                </div>
                <div class="col-md-6 text-end text-right">
                    <h5>Grand Total Estimated Cost: <span id="grand-total-display" class="text-success fw-bold">UGX 0.00</span></h5>
                </div>
            </div>

            <!-- SECTION 6: RANK-BASED APPROVAL ROUTING -->
            <h5 class="mb15 border-bottom pb10 text-primary mt15"><i data-feather="users" class="icon-16"></i> Section 6: Submit to Superior for Approval (Internal Department Chain)</h5>
            <div class="row mb20">
                <div class="col-md-8 form-group">
                    <label for="assigned_approver_id" class="control-label fw-bold">Select Superior Officer in Department <span class="text-danger">*</span></label>
                    <small class="text-muted d-block mb5">Bureaucracy Rule: Rank <?php echo $user_dept_info->role_rank; ?> officers submit to Rank <?php echo max(1, $user_dept_info->role_rank - 1); ?> in <strong><?php echo $user_dept_info->department_title; ?></strong>.</small>
                    <?php echo form_dropdown("assigned_approver_id", $superiors_dropdown, array(), "class='select2 form-control' id='assigned_approver_id' data-rule-required='true'"); ?>
                </div>
            </div>

            <input type="hidden" id="items_data" name="items_data" value="" />

            <hr class="mt25 mb20" />

            <div class="row">
                <div class="col-md-12 text-end text-right">
                    <button type="submit" id="btn-submit-form5" class="btn btn-primary p10"><i data-feather="send" class="icon-16 mr5"></i> Submit Form 5 to Superior for Approval</button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#procurement_type, #budget_category, #assigned_approver_id").select2();
        setDatePicker("#date_required");

        function calculateGrandTotal() {
            let total = 0;
            $("#form5-items-table tbody tr").each(function () {
                let qty = parseFloat($(this).find(".item-qty").val()) || 0;
                let cost = parseFloat($(this).find(".item-cost").val()) || 0;
                total += (qty * cost);
            });
            $("#grand-total-display").text("UGX " + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        }

        $(document).on("input change", ".item-qty, .item-cost", function () {
            calculateGrandTotal();
        });

        let rowCount = 1;
        $("#add-item-row-btn").click(function () {
            rowCount++;
            let newRow = `
                <tr>
                    <td>${rowCount}</td>
                    <td><input type="text" class="form-control item-desc" placeholder="Technical specs or scope" required /></td>
                    <td><input type="number" step="0.01" class="form-control item-qty" value="1" min="0.01" required /></td>
                    <td><input type="text" class="form-control item-uom" value="Pcs" required /></td>
                    <td><input type="number" step="0.01" class="form-control item-cost" value="0.00" min="0" required /></td>
                    <td><input type="number" step="0.01" class="form-control item-market" value="0.00" min="0" /></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row-btn"><i data-feather="x" class="icon-14"></i></button></td>
                </tr>
            `;
            $("#form5-items-table tbody").append(newRow);
            feather.replace();
        });

        $(document).on("click", ".remove-row-btn", function () {
            if ($("#form5-items-table tbody tr").length > 1) {
                $(this).closest("tr").remove();
                calculateGrandTotal();
            } else {
                appAlert.error("Form 5 requires at least one line item.");
            }
        });

        $("#procurement-form5-form").appForm({
            beforeSubmitted: function () {
                let itemsArray = [];
                $("#form5-items-table tbody tr").each(function () {
                    itemsArray.push({
                        description: $(this).find(".item-desc").val(),
                        quantity: $(this).find(".item-qty").val(),
                        unit_of_measure: $(this).find(".item-uom").val(),
                        estimated_unit_cost: $(this).find(".item-cost").val(),
                        market_price: $(this).find(".item-market").val()
                    });
                });
                $("#items_data").val(JSON.stringify(itemsArray));
            },
            onSuccess: function (result) {
                if (result.redirect_to) {
                    window.location.href = result.redirect_to;
                }
            }
        });
    });
</script>
