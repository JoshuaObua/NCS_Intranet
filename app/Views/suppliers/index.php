<div id="page-content" class="page-wrapper clearfix">
    <div class="card bg-white">
        <div class="page-title clearfix">
            <h1><i data-feather="truck" class="icon-24"></i> Suppliers Registry & Vendor Directory</h1>
            <div class="title-button-group">
                <?php echo anchor(get_uri("suppliers/contacts"), "<i data-feather='users' class='icon-16 mr5'></i> Supplier Contacts Directory", array("class" => "btn btn-outline-secondary")); ?>
                <?php echo modal_anchor(get_uri("suppliers/modal_form"), "<i data-feather='plus-circle' class='icon-16 mr5'></i> Add New Supplier Profile", array("class" => "btn btn-primary", "title" => "Add New Supplier Profile")); ?>
            </div>
        </div>

        <div class="p20">
            <!-- Summary Metric Cards -->
            <div class="row mb20">
                <div class="col-md-3">
                    <div class="card p15 border-start border-primary border-4 shadow-sm bg-light">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 text-primary me-3">
                                <i data-feather="truck" class="icon-32"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size:11px;">Total Registered Suppliers</h6>
                                <h3 class="mb-0 text-dark"><?php echo number_format($stats->total_suppliers ?: 0); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p15 border-start border-success border-4 shadow-sm bg-light">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 text-success me-3">
                                <i data-feather="check-circle" class="icon-32"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size:11px;">PPDA Pre-Qualified</h6>
                                <h3 class="mb-0 text-success"><?php echo number_format($stats->prequalified_count ?: 0); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p15 border-start border-warning border-4 shadow-sm bg-light">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 text-warning me-3">
                                <i data-feather="users" class="icon-32"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size:11px;">Total Supplier Contacts</h6>
                                <h3 class="mb-0 text-warning"><?php echo number_format($stats->total_contacts ?: 0); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p15 border-start border-danger border-4 shadow-sm bg-light">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 text-danger me-3">
                                <i data-feather="alert-triangle" class="icon-32"></i>
                            </div>
                            <div>
                                <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size:11px;">Blacklisted / Suspended</h6>
                                <h3 class="mb-0 text-danger"><?php echo number_format($stats->blacklisted_count ?: 0); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datatable -->
            <div class="table-responsive">
                <table id="suppliers-table" class="display" cellspacing="0" width="100%">            
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#suppliers-table").appTable({
            source: '<?php echo_uri("suppliers/list_data") ?>',
            columns: [
                {title: "ID", "class": "w50"},
                {title: "Supplier & Company Profile", "class": "w250"},
                {title: "Category", "class": "w150"},
                {title: "Statutory (PPDA / URA TIN)", "class": "w180"},
                {title: "Key Contact Person", "class": "w200"},
                {title: "Vendor Rating", "class": "w120 text-center"},
                {title: "Status", "class": "w130 text-center"},
                {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w120"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>
