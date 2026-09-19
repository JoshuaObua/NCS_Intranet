<div id="page-content" class="page-wrapper clearfix">
    <div class="card bg-white">
        <div class="page-title clearfix">
            <h1><i data-feather="users" class="icon-24"></i> Supplier Contacts Directory</h1>
            <div class="title-button-group">
                <?php echo anchor(get_uri("suppliers"), "<i data-feather='truck' class='icon-16 mr5'></i> Back to Suppliers Registry", array("class" => "btn btn-outline-secondary")); ?>
                <?php echo modal_anchor(get_uri("suppliers/contact_modal_form"), "<i data-feather='user-plus' class='icon-16 mr5'></i> Add New Supplier Contact", array("class" => "btn btn-primary", "title" => "Add New Supplier Contact")); ?>
            </div>
        </div>

        <div class="p20">
            <!-- Datatable -->
            <div class="table-responsive">
                <table id="supplier-contacts-table" class="display" cellspacing="0" width="100%">            
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#supplier-contacts-table").appTable({
            source: '<?php echo_uri("suppliers/contacts_list_data") ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Contact Person Name", "class": "w220"},
                {title: "Supplier Company", "class": "w250"},
                {title: "Job Title / Designation", "class": "w180"},
                {title: "Email Address", "class": "w200"},
                {title: "Phone Numbers", "class": "w180"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5]
        });
    });
</script>
