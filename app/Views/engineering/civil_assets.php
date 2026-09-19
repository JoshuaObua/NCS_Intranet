<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="map" class="icon-16 mr5"></i> Civil Infrastructure & Land Cadastral Register</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("engineering/verify_civil_asset_modal"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Register Land Parcel / Civil Structure", array("class" => "btn btn-primary", "title" => "Register Land Parcel / Civil Asset")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="civil-assets-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#civil-assets-table").appTable({
            source: '<?php echo get_uri("engineering/civil_assets_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Property Code", "class": "w140"},
                {title: "Asset Name & Deed No"},
                {title: "Location / Cadastral Plot", "class": "w160"},
                {title: "Acreage", "class": "w100"},
                {title: "Valuation (UGX)", "class": "w140 text-right"},
                {title: "Boundary", "class": "w110 text-center"},
                {title: "Encroachment", "class": "w120 text-center"},
                {title: "Last Survey", "class": "w120"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
