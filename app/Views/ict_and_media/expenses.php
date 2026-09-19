<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="dollar-sign" class="icon-16 mr5"></i> ICT & Media Operational Expenses, Software Licenses & Supplies</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("ict_and_media/expense_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Record ICT Expense", array("class" => "btn btn-primary", "title" => "Record IT / Media Expense")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="ict-expenses-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ict-expenses-table").appTable({
            source: '<?php echo get_uri("ict_and_media/expenses_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Ref No", "class": "w140"},
                {title: "Expense Title / Description"},
                {title: "Expense Category", "class": "w180"},
                {title: "Amount (UGX)", "class": "w140 text-right"},
                {title: "Expense Date", "class": "w110"},
                {title: "Vendor / Supplier", "class": "w180"},
                {title: "Approved By", "class": "w140"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
