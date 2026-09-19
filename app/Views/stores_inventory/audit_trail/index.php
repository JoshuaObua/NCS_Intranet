<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="activity" class="icon-16 mr5"></i> <?php echo app_lang("movement_audit_trail"); ?></h4>
        </div>

        <div class="card-body pt0">
            <table id="audit-trail-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Item Name</th>
                        <th>Action Type</th>
                        <th>Qty</th>
                        <th>From Location</th>
                        <th>To Location</th>
                        <th>Recorded By</th>
                        <th>Action Details</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#audit-trail-table").appTable({
            source: '<?php echo_uri("stores_inventory/audit_trail_list_data"); ?>',
            columns: [
                {title: "Timestamp"},
                {title: "Item Name"},
                {title: "Action Type"},
                {title: "Qty"},
                {title: "From Location"},
                {title: "To Location"},
                {title: "Recorded By"},
                {title: "Action Details"}
            ]
        });
    });
</script>
