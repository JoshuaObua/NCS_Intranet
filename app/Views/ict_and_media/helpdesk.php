<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="life-buoy" class="icon-16 mr5"></i> Enterprise IT Helpdesk & Service Tickets Queue</h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("ict_and_media/helpdesk_modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Create IT Ticket", array("class" => "btn btn-primary", "title" => "Log New IT Helpdesk Ticket")); ?>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="ict-helpdesk-table" class="display" cellspacing="0" width="100%"></table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#ict-helpdesk-table").appTable({
            source: '<?php echo get_uri("ict_and_media/helpdesk_list_data"); ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Ticket No", "class": "w130"},
                {title: "Subject / Issue"},
                {title: "Requester & Department", "class": "w200"},
                {title: "Category", "class": "w140"},
                {title: "Priority", "class": "w110 text-center"},
                {title: "Assigned Tech", "class": "w160"},
                {title: "Status", "class": "w110 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w80"}
            ]
        });
    });
</script>
