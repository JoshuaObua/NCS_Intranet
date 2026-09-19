<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="users" class="icon-16 mr5"></i> Approved Supplier Registry & PPDA Blacklist Registry</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("procurement/supplier_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Register New Supplier", array("class" => "btn btn-primary", "title" => "Register Approved Vendor")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="procurement-suppliers-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#procurement-suppliers-table").appTable({
            source: '<?php echo get_uri("procurement/suppliers_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Company Name"},
                {title: "PPDA Reg No", "class": "w150"},
                {title: "TIN Number", "class": "w120"},
                {title: "Contact Person", "class": "w150"},
                {title: "Contact Details", "class": "w180"},
                {title: "PPDA Compliance", "class": "w150 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
