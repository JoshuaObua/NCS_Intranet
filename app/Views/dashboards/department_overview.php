<style>
.ncs-overview { margin-bottom: 24px; }
.ncs-overview .ncs-heading { display:flex; flex-wrap:wrap; gap:12px; justify-content:space-between; align-items:center; margin:10px 0 18px; }
.ncs-overview .ncs-heading h3 { margin:0 0 5px; }
.ncs-overview .ncs-highlights { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; margin-bottom:18px; }
.ncs-overview .ncs-highlight { display:block; padding:18px; border-top:3px solid #2676bc; border-radius:6px; margin:0; }
.ncs-overview .ncs-highlight strong { display:block; font-size:28px; line-height:1.3; margin-top:8px; font-variant-numeric:tabular-nums; overflow-wrap:anywhere; }
.ncs-overview .ncs-departments { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; }
.ncs-overview .ncs-department { margin:0; padding:18px; min-width:0; }
.ncs-overview .ncs-department h4 { font-size:16px; margin:0 0 6px; }
.ncs-overview .ncs-period { font-size:12px; min-height:34px; margin:0 0 12px; }
.ncs-overview .ncs-metric { display:flex; justify-content:space-between; align-items:baseline; gap:12px; padding:9px 0; border-bottom:1px solid rgba(128,128,128,.15); }
.ncs-overview .ncs-metric span { font-size:13px; }
.ncs-overview .ncs-value { font-size:18px; white-space:nowrap; font-variant-numeric:tabular-nums; }
.ncs-overview .ncs-value[title] { cursor:help; }
.ncs-overview .ncs-checks { display:flex; flex-wrap:wrap; gap:10px; margin:0 0 20px; }
.ncs-overview .ncs-check { padding:10px 14px; border:1px solid rgba(128,128,128,.25); border-radius:5px; font-size:12px; }
.ncs-overview.ncs-exact .ncs-value { font-size:14px; white-space:normal; overflow-wrap:anywhere; text-align:right; }
.ncs-overview.ncs-exact .ncs-highlight strong { font-size:21px; }
@media(max-width:1199px) { .ncs-overview .ncs-departments { grid-template-columns:repeat(2,minmax(0,1fr)); } }
@media(max-width:767px) { .ncs-overview .ncs-highlights { grid-template-columns:repeat(2,minmax(0,1fr)); } .ncs-overview .ncs-departments { grid-template-columns:1fr; } .ncs-overview .ncs-highlight { padding:12px; } .ncs-overview .ncs-highlight strong { font-size:22px; } }
</style>
<section class="ncs-overview" id="department-overview" aria-label="Department totals and key performance indicators">
    <div class="ncs-heading">
        <div><h3>Organisation overview</h3><div class="text-muted">Live totals from your department registers</div></div>
        <div class="d-flex align-items-center gap-3">
            <label class="mb0"><input type="checkbox" id="kpi-exact-values"> Show exact figures</label>
            <button type="button" class="btn btn-default" id="refresh-department-totals"><i data-feather="refresh-cw" class="icon-16"></i> Refresh totals</button>
        </div>
    </div>
    <p id="kpi-refresh-status" class="text-muted small" role="status" aria-live="polite"></p>
    <div id="department-totals-content"><?php echo view('dashboards/department_totals', ['overview' => $overview]); ?></div>
</section>
<script>
$(function () {
    var busy = false, root = $('#department-overview');
    function applyNumberMode() {
        var exact = $('#kpi-exact-values').prop('checked');
        root.toggleClass('ncs-exact', exact);
        root.find('[data-kpi-compact]').each(function () {
            $(this).text($(this).attr(exact ? 'data-kpi-exact' : 'data-kpi-compact'));
        });
    }
    $('#kpi-exact-values').on('change', applyNumberMode);
    function refreshTotals() {
        if (busy || document.hidden) return;
        busy = true;
        $('#refresh-department-totals').prop('disabled', true);
        $('#kpi-refresh-status').text('Updating totals…');
        $.ajax({url: <?php echo json_encode(get_uri('dashboard/department_totals')); ?>, dataType:'json', cache:false})
            .done(function (data) {
                if (!data || typeof data.html !== 'string') {
                    $('#kpi-refresh-status').text('Could not refresh. Previously loaded totals are still shown.');
                    return;
                }
                $('#department-totals-content').html(data.html);
                applyNumberMode();
                feather.replace();
                $('#kpi-refresh-status').text('Totals updated.');
            })
            .fail(function () { $('#kpi-refresh-status').text('Could not refresh. Previously loaded totals are still shown. Try Refresh totals again.'); })
            .always(function () { busy = false; $('#refresh-department-totals').prop('disabled', false); });
    }
    $('#refresh-department-totals').on('click', refreshTotals);
    var refreshTimer = setInterval(refreshTotals, 60000);
    $(window).one('pagehide', function () { clearInterval(refreshTimer); });
});
</script>
