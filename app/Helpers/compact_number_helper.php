<?php

/** Display-only formatting. Keep raw database values for arithmetic and exports. */
function compact_number($value, int $precision = 2): string {
    if ($value === null || !is_numeric($value) || !is_finite((float) $value)) {
        return '—';
    }
    $number = (float) $value;
    $suffixes = ['', 'k', 'm', 'b', 't'];
    $scale = 0;
    while (abs($number) >= 1000 && $scale < count($suffixes) - 1) {
        $number /= 1000;
        $scale++;
    }
    if (abs(round($number, $precision)) >= 1000 && $scale < count($suffixes) - 1) {
        $number /= 1000;
        $scale++;
    }
    $text = number_format($number, $precision, '.', ',');
    if ($precision > 0) {
        $text = rtrim(rtrim($text, '0'), '.');
    }
    return ($text === '-0' ? '0' : $text) . $suffixes[$scale];
}

function compact_metric($value, string $unit = ''): string {
    $display = compact_number($value);
    if ($display === '—') {
        return $display;
    }
    return $unit === '%' ? $display . '%' : ($unit ? $unit . ' ' : '') . $display;
}

function exact_metric($value, string $unit = ''): string {
    if ($value === null || !is_numeric($value)) {
        return 'Not available';
    }
    $text = number_format((float) $value, $unit && $unit !== '%' ? 2 : 2, '.', ',');
    if (!$unit || $unit === '%') {
        $text = rtrim(rtrim($text, '0'), '.');
    }
    return $unit === '%' ? $text . '%' : ($unit ? $unit . ' ' : '') . $text;
}
