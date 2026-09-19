<?php
helper('compact_number');
$sections = $overview['sections'];
$render_value = static function ($metric, $class = 'ncs-value') {
    $compact = compact_metric($metric['value'], $metric['unit']);
    $exact = exact_metric($metric['value'], $metric['unit']);
    return '<strong class="' . esc($class) . '" title="' . esc($exact, 'attr') . '" data-kpi-compact="' . esc($compact, 'attr') . '" data-kpi-exact="' . esc($exact, 'attr') . '">' . esc($compact) . '</strong>';
};
?>
<div class="d-flex flex-wrap justify-content-between text-muted small mb15">
    <span>As of <?php echo esc($overview['date']); ?> · refreshed <?php echo esc(format_to_time($overview['updated_at'])); ?> · automatic refresh every minute</span>
    <span>k = thousand · m = million · b = billion · exact amounts on hover</span>
</div>
<div class="ncs-highlights">
<?php foreach (['assets' => ['total_assets', 'total_nbv'], 'inventory' => ['skus', 'value']] as $section_id => $keys) {
    if (empty($sections[$section_id]) || $sections[$section_id]['error']) continue;
    foreach ($sections[$section_id]['metrics'] as $metric) {
        if (!in_array($metric['key'], $keys, true)) continue;
?>
    <a class="card ncs-highlight" href="<?php echo get_uri($sections[$section_id]['url']); ?>">
        <span class="text-muted"><?php echo esc($metric['label']); ?></span>
        <?php echo $render_value($metric); ?>
        <small class="text-muted"><?php echo esc($sections[$section_id]['title']); ?> <i data-feather="arrow-up-right" class="icon-14"></i></small>
    </a>
<?php } } ?>
</div>
<div class="ncs-checks" aria-label="Reconciliation checks">
<?php foreach ($sections as $section) { foreach ($section['checks'] as $check) {
    $balanced = (int)$check['exceptions'] === 0 && ($check['difference'] === null || abs((float)$check['difference']) <= .01);
?>
    <div class="ncs-check" title="<?php echo esc($check['explanation'], 'attr'); ?>">
        <a href="<?php echo get_uri($section['url']); ?>"><?php echo esc($check['label']); ?></a>
        <strong class="<?php echo $balanced ? 'text-success' : 'text-danger'; ?>"><?php echo $balanced ? ' · Checks passed' : ' · Review needed'; ?></strong>
        <?php if (!$balanced) { ?><div><?php echo compact_number($check['exceptions']); ?> exception(s)<?php if ($check['difference'] !== null) echo ' · Difference ' . esc(exact_metric($check['difference'], $check['unit'])); ?></div><?php } ?>
    </div>
<?php } } ?>
</div>
<div class="ncs-departments">
<?php foreach ($sections as $section) { ?>
    <article class="card ncs-department" data-department="<?php echo esc($section['id']); ?>">
        <h4><a href="<?php echo get_uri($section['url']); ?>"><i data-feather="<?php echo esc($section['icon']); ?>" class="icon-16 mr5"></i><?php echo esc($section['title']); ?> <i data-feather="arrow-up-right" class="icon-14"></i></a></h4>
        <p class="text-muted ncs-period"><?php echo esc($section['period']); ?></p>
        <?php if ($section['error']) { ?>
            <p class="text-warning">Totals are temporarily unavailable. Open the register or refresh to try again.</p>
        <?php } else { foreach ($section['metrics'] as $metric) { ?>
            <div class="ncs-metric" data-metric="<?php echo esc($metric['key'], 'attr'); ?>" data-value="<?php echo esc((string)$metric['value'], 'attr'); ?>">
                <span><?php if ($metric['url']) { ?><a href="<?php echo get_uri($metric['url']); ?>"><?php echo esc($metric['label']); ?></a><?php } else { echo esc($metric['label']); } ?></span>
                <?php echo $render_value($metric); ?>
            </div>
        <?php } } ?>
    </article>
<?php } ?>
</div>
<p class="text-muted small mt15">Deleted records are excluded. Register totals can overlap (for example, equipment may also appear in fixed assets) and are not added into a combined total. A dash means a rate has no applicable denominator.</p>
