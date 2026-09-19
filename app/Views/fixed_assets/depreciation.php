<div id="page-content" class="page-wrapper clearfix">
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Standard Depreciation Method</div>
                <h3 class="fw-bold text-primary mb-0">IPSAS 17</h3>
                <small class="text-muted">Straight-Line Method</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Total Revalued Asset Base</div>
                <h3 class="fw-bold text-success mb-0">UGX 32.18B</h3>
                <small class="text-success">Land Exempted from Depr</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Accumulated Depreciation</div>
                <h3 class="fw-bold text-warning mb-0">UGX 0.00</h3>
                <small class="text-muted">Fresh Monthly Cycle</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm border-0 text-center">
                <div class="text-muted small">Net Book Value (NBV)</div>
                <h3 class="fw-bold text-info mb-0">UGX 32.18B</h3>
                <small class="text-success">100% Financial Integrity</small>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="trending-down" class="icon-16 mr5"></i> IPSAS 17 Straight-Line Depreciation Engine & Monthly Schedule Runner</h4>
            <div class="title-button-group">
                <button class="btn btn-primary btn-sm" id="btn-run-depr"><i data-feather="play" class="icon-16"></i> Run Monthly Depreciation Schedule</button>
                <button class="btn btn-outline-secondary btn-sm" onclick="location.reload();"><i data-feather="refresh-cw" class="icon-16"></i> Refresh Schedule</button>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-warning p-3 mb-3">
                <h5 class="fw-bold mb-1"><i data-feather="alert-circle" class="icon-16"></i> IPSAS 17 Depreciation Rules</h5>
                <ul class="mb-0 small">
                    <li><strong>Land & Naturally Occurring Assets:</strong> Zero depreciation applied (Infinite Useful Life).</li>
                    <li><strong>Buildings & Infrastructural Structures:</strong> 5% annual straight-line rate (20-year useful life).</li>
                    <li><strong>Vehicles & Transport Equipment:</strong> 20% annual straight-line rate (5-year useful life).</li>
                    <li><strong>Machinery & ICT Hardware:</strong> 20% annual straight-line rate (5-year useful life).</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#btn-run-depr").click(function() {
            if (confirm("Are you sure you want to execute the monthly IPSAS 17 straight-line depreciation run across all active fixed assets?")) {
                $.ajax({
                    url: '<?php echo get_uri("fixed_assets/run_depreciation"); ?>',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            appNotice.success(response.message);
                            location.reload();
                        } else {
                            appNotice.error(response.message);
                        }
                    }
                });
            }
        });
    });
</script>
