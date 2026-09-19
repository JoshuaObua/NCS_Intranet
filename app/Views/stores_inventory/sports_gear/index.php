<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="package" class="icon-16 mr5"></i> <?php echo $title; ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("stores_inventory/modal_form"), "<i data-feather='plus' class='icon-16'></i> " . app_lang("add_new_inventory_record"), array("class" => "btn btn-primary", "title" => app_lang("add_new_inventory_record"))); ?>
            </div>
        </div>

        <div class="card-body pt0">
            <table id="category-items-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>SKU Code</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Qty on Hand</th>
                        <th>Unit Cost</th>
                        <th>Total Value</th>
                        <th>Bin Location</th>
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
        $("#category-items-table").appTable({
            source: '<?php echo_uri("stores_inventory/list_data"); ?>',
            postData: {category: "<?php echo $category; ?>"},
            columns: [
                {title: "SKU Code"},
                {title: "Item Name"},
                {title: "Category"},
                {title: "Qty on Hand"},
                {title: "Unit Cost"},
                {title: "Total Value"},
                {title: "Bin Location"},
                {title: "Status"},
                {title: "<i data-feather='menu' class='icon-16'></i>", class: "text-center option w100"}
            ]
        });
    });
</script>
