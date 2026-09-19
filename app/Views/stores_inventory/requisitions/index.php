<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="check-square" class="icon-16 mr5"></i> <?php echo app_lang("requisitions"); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("stores_inventory/requisition_modal_form"), "<i data-feather='plus' class='icon-16'></i> Submit Requisition", array("class" => "btn btn-primary", "title" => "Submit Material Requisition")); ?>
            </div>
        </div>

        <div class="card-body pt0">
            <table id="requisitions-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Req Number</th>
                        <th>Requested By</th>
                        <th>Department</th>
                        <th>Target Office</th>
                        <th>Reason for Requisition</th>
                        <th>Requested Date</th>
                        <th>Status</th>
                        <th class="text-center option w100"><?php echo app_lang("action"); ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#requisitions-table").appTable({
            source: '<?php echo_uri("stores_inventory/requisitions_list_data"); ?>',
            columns: [
                {title: "Req Number"},
                {title: "Requested By"},
                {title: "Department"},
                {title: "Target Office"},
                {title: "Reason"},
                {title: "Requested Date"},
                {title: "Status"},
                {title: "<i data-feather='menu' class='icon-16'></i>", class: "text-center option w100"}
            ]
        });
    });
</script>
