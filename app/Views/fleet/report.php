<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="pie-chart" class="icon-16 mr5"></i> <?php echo app_lang("fleet_report"); ?></h4>
            <div class="title-button-group">
                <?php echo anchor(get_uri("fleet"), "<i data-feather='truck' class='icon-16'></i> " . app_lang("fleet_vehicles"), array("class" => "btn btn-default")); ?>
            </div>
        </div>

        <div class="card-body">

            <!-- Status Summary -->
            <div class="row mb20">
                <?php
                $status_map = array(
                    "available"      => array("label" => app_lang("fleet_available"),      "color" => "success",   "icon" => "check-circle"),
                    "in_field"       => array("label" => app_lang("fleet_in_field"),       "color" => "primary",   "icon" => "map-pin"),
                    "maintenance"    => array("label" => app_lang("fleet_maintenance"),    "color" => "warning",   "icon" => "tool"),
                    "decommissioned" => array("label" => app_lang("fleet_decommissioned"), "color" => "secondary", "icon" => "x-circle"),
                );
                $status_data = json_decode($status_data, true);
                foreach ($status_map as $key => $info) {
                    $count = get_array_value($status_data, $key) ?: 0;
                ?>
                <div class="col-md-3 col-sm-6 mb15">
                    <div class="card border-<?php echo $info['color']; ?>">
                        <div class="card-body text-center">
                            <i data-feather="<?php echo $info['icon']; ?>" class="icon-32 text-<?php echo $info['color']; ?>"></i>
                            <h2 class="mt5 mb0 text-<?php echo $info['color']; ?>"><?php echo $count; ?></h2>
                            <small><?php echo $info['label']; ?></small>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><?php echo app_lang("fleet_status_distribution"); ?></h5>
                        </div>
                        <div class="card-body">
                            <canvas id="fleet-status-chart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <!-- Service Due -->
                    <div class="card mb15">
                        <div class="card-header">
                            <h5><i data-feather="alert-triangle" class="icon-14 text-warning mr5"></i> <?php echo app_lang("fleet_service_due_soon"); ?> (<?php echo count($service_due); ?>)</h5>
                        </div>
                        <div class="card-body p0">
                            <?php if (count($service_due)): ?>
                            <table class="table table-sm mb0">
                                <thead>
                                    <tr>
                                        <th><?php echo app_lang("plate_number"); ?></th>
                                        <th><?php echo app_lang("vehicle"); ?></th>
                                        <th><?php echo app_lang("fleet_next_service"); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($service_due as $v): ?>
                                    <tr>
                                        <td><?php echo anchor(get_uri("fleet/view/" . $v->id), "<strong>" . $v->plate_number . "</strong>"); ?></td>
                                        <td><?php echo $v->make . " " . $v->model; ?></td>
                                        <td class="<?php echo strtotime($v->next_service_date) < time() ? 'text-danger' : 'text-warning'; ?>">
                                            <?php echo format_to_date($v->next_service_date, false); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="p15 text-muted text-center"><?php echo app_lang("no_service_due"); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent Service Logs -->
                    <div class="card">
                        <div class="card-header">
                            <h5><i data-feather="tool" class="icon-14 mr5"></i> <?php echo app_lang("fleet_recent_service"); ?></h5>
                        </div>
                        <div class="card-body p0">
                            <?php if (count($recent_service)): ?>
                            <table class="table table-sm mb0">
                                <thead>
                                    <tr>
                                        <th><?php echo app_lang("vehicle"); ?></th>
                                        <th><?php echo app_lang("fleet_service_type"); ?></th>
                                        <th><?php echo app_lang("fleet_service_date"); ?></th>
                                        <th><?php echo app_lang("fleet_service_cost"); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($recent_service, 0, 10) as $log): ?>
                                    <tr>
                                        <td><?php echo $log->vehicle_label ?: $log->plate_number; ?></td>
                                        <td><?php echo $log->service_type; ?></td>
                                        <td><?php echo format_to_date($log->service_date, false); ?></td>
                                        <td><?php echo format_currency($log->cost); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="p15 text-muted text-center"><?php echo app_lang("no_records_found"); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script>
<script>
$(document).ready(function () {
    var statusData = <?php echo json_encode($status_data ?? []); ?>;
    var labels = [], data = [], colors = [];
    var colorMap = {available: '#28a745', in_field: '#007bff', maintenance: '#ffc107', decommissioned: '#6c757d'};
    $.each(statusData, function(k, v) {
        labels.push(k.replace('_', ' '));
        data.push(v);
        colors.push(colorMap[k] || '#999');
    });

    if (data.length) {
        new Chart(document.getElementById('fleet-status-chart'), {
            type: 'doughnut',
            data: {labels: labels, datasets: [{data: data, backgroundColor: colors}]},
            options: {plugins: {legend: {position: 'bottom'}}}
        });
    }

    feather.replace();
});
</script>
