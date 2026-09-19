<div id="page-content" class="page-wrapper clearfix">
    <div class="card">
        <div class="page-title clearfix">
            <h4><i data-feather="award" class="icon-16 mr5"></i> <?php echo htmlspecialchars($appraisal->period_name); ?> &mdash; <?php echo $appraisal->employee_name; ?></h4>
            <div class="title-button-group">
                <?php echo anchor(get_uri("hr_appraisals"), "<i data-feather='arrow-left' class='icon-16'></i> " . app_lang("back"), array("class" => "btn btn-default")); ?>
                <?php if ($is_own && $appraisal->status === "draft") { ?>
                    <?php echo js_anchor("<i data-feather='send' class='icon-16'></i> " . app_lang("hr_self_submitted"), array("class" => "btn btn-primary btn-submit-self")); ?>
                <?php } ?>
                <?php if ($is_supervisor && $appraisal->status === "self_submitted") { ?>
                    <?php echo js_anchor("<i data-feather='check' class='icon-16'></i> " . app_lang("hr_supervisor_reviewed"), array("class" => "btn btn-success btn-supervisor-review")); ?>
                <?php } ?>
                <?php if ($can_manage && $appraisal->status === "supervisor_reviewed") { ?>
                    <?php echo js_anchor("<i data-feather='check-circle' class='icon-16'></i> " . app_lang("hr_completed"), array("class" => "btn btn-success btn-complete")); ?>
                <?php } ?>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb20">
                <div class="col-md-6">
                    <table class="table table-sm table-bordered">
                        <tr><th><?php echo app_lang("employee"); ?></th><td><?php echo $appraisal->employee_name; ?> <small class="text-muted"><?php echo $appraisal->employee_job_title; ?></small></td></tr>
                        <tr><th><?php echo app_lang("supervisor"); ?></th><td><?php echo $appraisal->supervisor_name; ?></td></tr>
                        <tr><th><?php echo app_lang("period"); ?></th><td><?php echo $appraisal->period_name; ?> (<?php echo $appraisal->period_year; ?>)</td></tr>
                        <tr><th><?php echo app_lang("status"); ?></th>
                            <td><?php
                                $badges = ["draft" => "secondary", "self_submitted" => "info", "supervisor_reviewed" => "warning", "completed" => "success"];
                                $b = $badges[$appraisal->status] ?? "secondary";
                                echo "<span class='badge bg-{$b}'>" . app_lang("hr_" . $appraisal->status) . "</span>";
                            ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- KPI Items -->
            <h5><?php echo app_lang("kpi_items"); ?></h5>
            <?php if (empty($items)) { ?>
                <p class="text-muted"><?php echo app_lang("no_records_found"); ?></p>
            <?php } else { ?>
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th><?php echo app_lang("section"); ?></th>
                        <th><?php echo app_lang("kpi"); ?></th>
                        <th><?php echo app_lang("target"); ?></th>
                        <th><?php echo app_lang("self_score"); ?></th>
                        <th><?php echo app_lang("supervisor_score"); ?></th>
                        <th><?php echo app_lang("comments"); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item->section); ?></td>
                        <td><?php echo htmlspecialchars($item->kpi); ?></td>
                        <td><?php echo htmlspecialchars($item->target); ?></td>
                        <td><?php echo $item->self_score !== null ? $item->self_score : "-"; ?></td>
                        <td><?php echo $item->supervisor_score !== null ? $item->supervisor_score : "-"; ?></td>
                        <td><?php echo htmlspecialchars($item->comments); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".btn-submit-self").on("click", function () {
        appSave(get_uri("hr_appraisals/submit_self"), {id: <?php echo $appraisal->id; ?>}, function () { window.location.reload(); });
    });
    $(".btn-supervisor-review").on("click", function () {
        appSave(get_uri("hr_appraisals/supervisor_review"), {id: <?php echo $appraisal->id; ?>}, function () { window.location.reload(); });
    });
    $(".btn-complete").on("click", function () {
        appSave(get_uri("hr_appraisals/complete"), {id: <?php echo $appraisal->id; ?>}, function () { window.location.reload(); });
    });
});
</script>
