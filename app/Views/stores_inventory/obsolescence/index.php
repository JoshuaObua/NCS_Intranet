<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="alert-triangle" class="icon-16 mr5"></i> <?php echo app_lang("obsolescence_flagging"); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("stores_inventory/defect_modal_form"), "<i data-feather='plus' class='icon-16'></i> " . app_lang("report_defect"), array("class" => "btn btn-primary", "title" => "Report Defective Item / Flag Obsolescence")); ?>
            </div>
        </div>

        <div class="card-body pt0">
            <table id="obsolescence-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>SKU Code</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Qty on Hand</th>
                        <th>Bin Location</th>
                        <th>Condition</th>
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
        $("#obsolescence-table").appTable({
            source: '<?php echo_uri("stores_inventory/list_data"); ?>',
            filterDropdown: [
                {name: "condition", class: "w200", options: [
                    {id: "defective", text: "Defective / Faulty"},
                    {id: "obsolete", text: "Obsolete / Board of Survey"},
                    {id: "fair", text: "Fair / Damaged"}
                ]}
            ],
            columns: [
                {title: "SKU Code"},
                {title: "Item Name"},
                {title: "Category"},
                {title: "Qty on Hand"},
                {title: "Bin Location"},
                {title: "Status"},
                {title: "<i data-feather='menu' class='icon-16'></i>", class: "text-center option w100"}
            ]
        });
    });
</script>
