<?php

namespace App\Services;

class ColorPaletteService
{
    public static function fromTwo(string $primary, string $secondary): array
    {
        return [
            'primary'         => $primary,
            'primary_light'   => self::lighten($primary, 60),
            'primary_dark'    => self::darken($primary, 25),
            'primary_text'    => self::accessibleText($primary),
            'secondary'       => $secondary,
            'secondary_light' => self::lighten($secondary, 60),
            'secondary_dark'  => self::darken($secondary, 25),
            'secondary_text'  => self::accessibleText($secondary),
        ];
    }

    private static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Relative luminance per WCAG 2.1 §1.4.3.
     */
    private static function luminance(array $rgb): float
    {
        $channels = array_map(fn($c) => ($c / 255) <= 0.03928
            ? ($c / 255) / 12.92
            : (($c / 255 + 0.055) / 1.055) ** 2.4, $rgb);

        return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
    }

    private static function contrastRatio(float $l1, float $l2): float
    {
        [$hi, $lo] = $l1 > $l2 ? [$l1, $l2] : [$l2, $l1];
        return ($hi + 0.05) / ($lo + 0.05);
    }

    /**
     * Returns #ffffff or #000000 — whichever has the higher contrast on $hex.
     */
    private static function accessibleText(string $hex): string
    {
        $lum = self::luminance(self::hexToRgb($hex));
        $onWhite = self::contrastRatio(1.0, $lum);
        $onBlack = self::contrastRatio(0.0, $lum);

        return $onWhite >= $onBlack ? '#ffffff' : '#000000';
    }

    private static function lighten(string $hex, int $percent): string
    {
        [$r, $g, $b] = self::hexToRgb($hex);
        return sprintf('#%02x%02x%02x',
            (int) ($r + (255 - $r) * $percent / 100),
            (int) ($g + (255 - $g) * $percent / 100),
            (int) ($b + (255 - $b) * $percent / 100),
        );
    }

    private static function darken(string $hex, int $percent): string
    {
        [$r, $g, $b] = self::hexToRgb($hex);
        return sprintf('#%02x%02x%02x',
            (int) ($r * (1 - $percent / 100)),
            (int) ($g * (1 - $percent / 100)),
            (int) ($b * (1 - $percent / 100)),
        );
    }
}
