<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="send" class="icon-16 mr5"></i> <?php echo app_lang("issuances"); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("stores_inventory/issuance_modal_form"), "<i data-feather='plus' class='icon-16'></i> Issue / Reissue Stock", array("class" => "btn btn-primary", "title" => "Issue or Re-issue Store Items")); ?>
            </div>
        </div>

        <div class="card-body pt0">
            <table id="issuances-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>SIV Number</th>
                        <th>Item & SKU</th>
                        <th>Qty Issued</th>
                        <th>Department</th>
                        <th>Recipient User</th>
                        <th>Condition on Issue</th>
                        <th>Issued By</th>
                        <th>Issued Date</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#issuances-table").appTable({
            source: '<?php echo_uri("stores_inventory/issuances_list_data"); ?>',
            columns: [
                {title: "SIV Number"},
                {title: "Item"},
                {title: "Qty Issued"},
                {title: "Department"},
                {title: "Recipient User"},
                {title: "Condition"},
                {title: "Issued By"},
                {title: "Issued Date"}
            ]
        });
    });
</script>
