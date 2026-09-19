<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="box" class="icon-16 mr5"></i> <?php echo app_lang("store_and_inventory"); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("stores_inventory/modal_form"), "<i data-feather='plus' class='icon-16'></i> " . app_lang("add_new_inventory_record"), array("class" => "btn btn-primary", "title" => app_lang("add_new_inventory_record"))); ?>
            </div>
        </div>

        <!-- KPI summary cards -->
        <div class="row p15 pb0">
            <?php
            $cat_map = array(
                "sports_gear"        => array("label" => app_lang("sports_gear"),        "icon" => "dribbble", "color" => "primary"),
                "engineering_spares" => array("label" => app_lang("engineering_spares"), "icon" => "tool",     "color" => "warning"),
                "ict_consumables"    => array("label" => app_lang("ict_consumables"),    "icon" => "cpu",      "color" => "info"),
                "office_supplies"    => array("label" => app_lang("office_supplies"),    "icon" => "package",  "color" => "success"),
            );
            $summary_data = array();
            foreach ($category_summary as $c) { $summary_data[$c->category] = $c; }
            foreach ($cat_map as $key => $info) {
                $item_cat = get_array_value($summary_data, $key);
                $skus = $item_cat ? $item_cat->total_skus : 0;
                $val  = $item_cat ? to_currency($item_cat->category_value) : "UGX 0";
            ?>
            <div class="col-md-3 col-sm-6 mb15">
                <div class="card border-<?php echo $info['color']; ?> h-100">
                    <div class="card-body text-center">
                        <i data-feather="<?php echo $info['icon']; ?>" class="icon-32 text-<?php echo $info['color']; ?>"></i>
                        <h4 class="mt5 mb0"><?php echo $info['label']; ?></h4>
                        <p class="text-muted mb0"><?php echo $skus; ?> SKUs | Valuation: <?php echo $val; ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="card-body pt0">
            <div class="row mb15">
                <div class="col-md-3">
                    <select id="store-category-filter" class="form-control select2">
                        <option value=""><?php echo "-- " . app_lang("all_categories") . " --"; ?></option>
                        <option value="sports_gear"><?php echo app_lang("sports_gear"); ?></option>
                        <option value="engineering_spares"><?php echo app_lang("engineering_spares"); ?></option>
                        <option value="ict_consumables"><?php echo app_lang("ict_consumables"); ?></option>
                        <option value="office_supplies"><?php echo app_lang("office_supplies"); ?></option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="store-status-filter" class="form-control select2">
                        <option value=""><?php echo "-- " . app_lang("all_status") . " --"; ?></option>
                        <option value="in_stock"><?php echo app_lang("in_stock"); ?></option>
                        <option value="low_stock"><?php echo app_lang("low_stock"); ?></option>
                        <option value="out_of_stock"><?php echo app_lang("out_of_stock"); ?></option>
                        <option value="reissued"><?php echo app_lang("reissued"); ?></option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" id="store-search" class="form-control" placeholder="<?php echo app_lang('search'); ?>" />
                </div>
            </div>

            <table id="inventory-items-table" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>SKU Code</th>
                        <th><?php echo app_lang("item"); ?></th>
                        <th><?php echo app_lang("category"); ?></th>
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
        $("#inventory-items-table").appTable({
            source: '<?php echo_uri("stores_inventory/list_data"); ?>',
            filterDropdown: [
                {name: "category", class: "w200", options: [
                    {id: "", text: "- All Categories -"},
                    {id: "sports_gear", text: "Sports Equipment & Gear"},
                    {id: "engineering_spares", text: "Engineering Spares"},
                    {id: "ict_consumables", text: "ICT Consumables"},
                    {id: "office_supplies", text: "Office Supplies"}
                ]}
            ],
            columns: [
                {title: "SKU Code"},
                {title: "<?php echo app_lang('item'); ?>"},
                {title: "<?php echo app_lang('category'); ?>"},
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
