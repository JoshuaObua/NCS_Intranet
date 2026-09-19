<?php echo form_open(get_uri("legal_compliance/save_contract"), array("id" => "contract-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class="row">
            <label for="contract_ref" class="col-md-3">Contract Ref</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "contract_ref",
                    "name" => "contract_ref",
                    "value" => $model_info->contract_ref ?: "NCS/LEGAL/2026/00" . rand(4,9),
                    "class" => "form-control",
                    "placeholder" => "e.g. NCS/LEGAL/2026/001",
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
            <label for="title" class="col-md-3">Contract Title</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "title",
                    "name" => "title",
                    "value" => $model_info->title,
                    "class" => "form-control",
                    "placeholder" => "Title or Subject of Agreement",
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
                        "CIVIL_WORKS" => "Civil Works & Infrastructure Construction",
                        "FEDERATION_MOU" => "National Federation MOU & Funding Framework",
                        "SUPPLIER_PROCUREMENT" => "Supplier Procurement & Goods Supply",
                        "BROADCAST_SPONSORSHIP" => "Broadcast, Media & Commercial Sponsorship",
                        "CONSULTANCY_SERVICES" => "Consultancy & Legal Advisory Services"
                    ),
                    $model_info->category ?: "CIVIL_WORKS",
                    "class='form-control select2' id='category'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="parties_involved" class="col-md-3">Parties Involved</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "parties_involved",
                    "name" => "parties_involved",
                    "value" => $model_info->parties_involved,
                    "class" => "form-control",
                    "placeholder" => "e.g. National Council of Sports & Roko Construction Ltd"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="contract_value" class="col-md-3">Contract Value (UGX)</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "contract_value",
                    "name" => "contract_value",
                    "value" => $model_info->contract_value,
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
            <label for="effective_date" class="col-md-3">Effective Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "effective_date",
                    "name" => "effective_date",
                    "value" => $model_info->effective_date ?: date("Y-m-d"),
                    "class" => "form-control",
                    "type" => "date"
                ));
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="expiry_date" class="col-md-3">Expiry Date</label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "expiry_date",
                    "name" => "expiry_date",
                    "value" => $model_info->expiry_date ?: date("Y-m-d", strtotime("+1 year")),
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
                        "ACTIVE" => "ACTIVE (Enforceable Contract)",
                        "UNDER_REVIEW" => "UNDER REVIEW (Solicitor General Vetting)",
                        "EXPIRED" => "EXPIRED (Lapsed)",
                        "TERMINATED" => "TERMINATED"
                    ),
                    $model_info->status ?: "ACTIVE",
                    "class='form-control select2' id='status'"
                );
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label for="notes" class="col-md-3">Key Terms & Notes</label>
            <div class="col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "notes",
                    "name" => "notes",
                    "value" => $model_info->notes,
                    "class" => "form-control",
                    "placeholder" => "Key covenants, renewal clauses, penalty conditions...",
                    "rows" => 3
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> Close</button>
    <button type="submit" class="btn btn-primary"><span data-feather="check" class="icon-16"></span> Save Contract</button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#contract-form").appForm({
            onSuccess: function (result) {
                $("#contracts-vault-table").appTable({newData: result.data, dataId: result.id});
                window.location.reload();
            }
        });
        $(".select2").select2();
    });
</script>
