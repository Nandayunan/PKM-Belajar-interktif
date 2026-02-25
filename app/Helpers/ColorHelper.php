<?php

/**
 * Adjust color brightness
 * @param string $color - Hex color code (e.g., #6366f1)
 * @param int $percent - Percentage to adjust (-100 to 100)
 * @return string - Adjusted hex color code
 */
function adjustColor($color, $percent)
{
    // Remove # if present
    $color = str_replace('#', '', $color);

    // Convert hex to RGB
    if (strlen($color) == 3) {
        $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
    }

    $r = hexdec(substr($color, 0, 2));
    $g = hexdec(substr($color, 2, 2));
    $b = hexdec(substr($color, 4, 2));

    // Adjust brightness
    $r = (int)($r * (1 + $percent / 100));
    $g = (int)($g * (1 + $percent / 100));
    $b = (int)($b * (1 + $percent / 100));

    // Ensure values are within 0-255
    $r = max(0, min(255, $r));
    $g = max(0, min(255, $g));
    $b = max(0, min(255, $b));

    // Convert back to hex
    return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT) .
        str_pad(dechex($g), 2, '0', STR_PAD_LEFT) .
        str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
}
