<?php

namespace App\Services\Branding;

class ColorSchemeGenerator
{
    /**
     * Industry-specific color palettes
     */
    protected const INDUSTRY_PALETTES = [
        'technology' => [
            ['primary' => '#3B82F6', 'secondary' => '#8B5CF6', 'accent' => '#10B981'],
            ['primary' => '#06B6D4', 'secondary' => '#6366F1', 'accent' => '#F59E0B'],
            ['primary' => '#8B5CF6', 'secondary' => '#EC4899', 'accent' => '#10B981'],
        ],
        'finance' => [
            ['primary' => '#1E40AF', 'secondary' => '#059669', 'accent' => '#F59E0B'],
            ['primary' => '#065F46', 'secondary' => '#1F2937', 'accent' => '#10B981'],
            ['primary' => '#1E3A8A', 'secondary' => '#475569', 'accent' => '#3B82F6'],
        ],
        'healthcare' => [
            ['primary' => '#0EA5E9', 'secondary' => '#10B981', 'accent' => '#06B6D4'],
            ['primary' => '#059669', 'secondary' => '#0284C7', 'accent' => '#10B981'],
            ['primary' => '#06B6D4', 'secondary' => '#6366F1', 'accent' => '#14B8A6'],
        ],
        'local_services' => [
            ['primary' => '#F59E0B', 'secondary' => '#3B82F6', 'accent' => '#10B981'],
            ['primary' => '#EF4444', 'secondary' => '#F59E0B', 'accent' => '#3B82F6'],
            ['primary' => '#10B981', 'secondary' => '#06B6D4', 'accent' => '#F59E0B'],
        ],
        'creative' => [
            ['primary' => '#EC4899', 'secondary' => '#8B5CF6', 'accent' => '#F97316'],
            ['primary' => '#F97316', 'secondary' => '#EC4899', 'accent' => '#8B5CF6'],
            ['primary' => '#8B5CF6', 'secondary' => '#F97316', 'accent' => '#06B6D4'],
        ],
        'professional_services' => [
            ['primary' => '#1E40AF', 'secondary' => '#64748B', 'accent' => '#0EA5E9'],
            ['primary' => '#475569', 'secondary' => '#1E40AF', 'accent' => '#3B82F6'],
            ['primary' => '#1F2937', 'secondary' => '#3B82F6', 'accent' => '#10B981'],
        ],
        'ecommerce' => [
            ['primary' => '#EF4444', 'secondary' => '#F59E0B', 'accent' => '#10B981'],
            ['primary' => '#8B5CF6', 'secondary' => '#EC4899', 'accent' => '#F59E0B'],
            ['primary' => '#F59E0B', 'secondary' => '#EF4444', 'accent' => '#10B981'],
        ],
    ];

    /**
     * Generate complementary color palette from primary color
     */
    public function generatePalette(string $primaryColor, string $mood = 'balanced'): array
    {
        $rgb = $this->hexToRgb($primaryColor);

        return [
            'primary' => $primaryColor,
            'secondary' => $this->generateSecondaryColor($rgb, $mood),
            'accent' => $this->generateAccentColor($rgb, $mood),
            'success' => '#10B981',
            'warning' => '#F59E0B',
            'error' => '#EF4444',
            'neutral_50' => '#F9FAFB',
            'neutral_100' => '#F3F4F6',
            'neutral_900' => '#111827',
        ];
    }

    /**
     * Suggest colors based on industry
     */
    public function suggestByIndustry(string $industry): array
    {
        $palettes = self::INDUSTRY_PALETTES[$industry] ?? self::INDUSTRY_PALETTES['technology'];

        // Return a random palette from the industry
        $selected = $palettes[array_rand($palettes)];

        return array_merge($selected, [
            'success' => '#10B981',
            'warning' => '#F59E0B',
            'error' => '#EF4444',
            'neutral_50' => '#F9FAFB',
            'neutral_100' => '#F3F4F6',
            'neutral_900' => '#111827',
        ]);
    }

    /**
     * Get all available industry options
     */
    public function getAvailableIndustries(): array
    {
        return array_keys(self::INDUSTRY_PALETTES);
    }

    /**
     * Validate color contrast for accessibility (WCAG AA)
     */
    public function validateContrast(string $foreground, string $background): bool
    {
        $fgRgb = $this->hexToRgb($foreground);
        $bgRgb = $this->hexToRgb($background);

        $fgLuminance = $this->calculateLuminance($fgRgb);
        $bgLuminance = $this->calculateLuminance($bgRgb);

        $contrastRatio = $this->calculateContrastRatio($fgLuminance, $bgLuminance);

        // WCAG AA requires 4.5:1 for normal text, 3:1 for large text
        return $contrastRatio >= 4.5;
    }

    /**
     * Generate color variations (shades/tints)
     */
    public function generateVariations(string $baseColor): array
    {
        $rgb = $this->hexToRgb($baseColor);

        return [
            '50' => $this->rgbToHex($this->adjustBrightness($rgb, 0.95)),
            '100' => $this->rgbToHex($this->adjustBrightness($rgb, 0.9)),
            '200' => $this->rgbToHex($this->adjustBrightness($rgb, 0.75)),
            '300' => $this->rgbToHex($this->adjustBrightness($rgb, 0.5)),
            '400' => $this->rgbToHex($this->adjustBrightness($rgb, 0.25)),
            '500' => $baseColor,
            '600' => $this->rgbToHex($this->adjustBrightness($rgb, -0.2)),
            '700' => $this->rgbToHex($this->adjustBrightness($rgb, -0.4)),
            '800' => $this->rgbToHex($this->adjustBrightness($rgb, -0.6)),
            '900' => $this->rgbToHex($this->adjustBrightness($rgb, -0.8)),
        ];
    }

    /**
     * Generate analogous colors (colors adjacent on color wheel)
     */
    public function generateAnalogous(string $baseColor): array
    {
        $hsl = $this->hexToHsl($baseColor);

        return [
            'base' => $baseColor,
            'plus_30' => $this->hslToHex([($hsl[0] + 30) % 360, $hsl[1], $hsl[2]]),
            'minus_30' => $this->hslToHex([($hsl[0] - 30 + 360) % 360, $hsl[1], $hsl[2]]),
        ];
    }

    /**
     * Generate complementary color (opposite on color wheel)
     */
    public function generateComplementary(string $baseColor): string
    {
        $hsl = $this->hexToHsl($baseColor);

        return $this->hslToHex([($hsl[0] + 180) % 360, $hsl[1], $hsl[2]]);
    }

    /**
     * Generate triadic colors (evenly spaced on color wheel)
     */
    public function generateTriadic(string $baseColor): array
    {
        $hsl = $this->hexToHsl($baseColor);

        return [
            'base' => $baseColor,
            'second' => $this->hslToHex([($hsl[0] + 120) % 360, $hsl[1], $hsl[2]]),
            'third' => $this->hslToHex([($hsl[0] + 240) % 360, $hsl[1], $hsl[2]]),
        ];
    }

    /**
     * Convert hex color to RGB
     */
    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Convert RGB to hex
     */
    protected function rgbToHex(array $rgb): string
    {
        $r = max(0, min(255, round($rgb['r'])));
        $g = max(0, min(255, round($rgb['g'])));
        $b = max(0, min(255, round($rgb['b'])));

        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }

    /**
     * Convert hex to HSL
     */
    protected function hexToHsl(string $hex): array
    {
        $rgb = $this->hexToRgb($hex);
        $r = $rgb['r'] / 255;
        $g = $rgb['g'] / 255;
        $b = $rgb['b'] / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $delta = $max - $min;

        $l = ($max + $min) / 2;

        if ($delta == 0) {
            $h = $s = 0;
        } else {
            $s = $l > 0.5 ? $delta / (2 - $max - $min) : $delta / ($max + $min);

            switch ($max) {
                case $r:
                    $h = (($g - $b) / $delta + ($g < $b ? 6 : 0)) / 6;
                    break;
                case $g:
                    $h = (($b - $r) / $delta + 2) / 6;
                    break;
                case $b:
                    $h = (($r - $g) / $delta + 4) / 6;
                    break;
            }
        }

        return [$h * 360, $s, $l];
    }

    /**
     * Convert HSL to hex
     */
    protected function hslToHex(array $hsl): string
    {
        [$h, $s, $l] = $hsl;
        $h /= 360;

        if ($s == 0) {
            $r = $g = $b = $l * 255;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;

            $r = $this->hueToRgb($p, $q, $h + 1 / 3) * 255;
            $g = $this->hueToRgb($p, $q, $h) * 255;
            $b = $this->hueToRgb($p, $q, $h - 1 / 3) * 255;
        }

        return $this->rgbToHex(['r' => $r, 'g' => $g, 'b' => $b]);
    }

    /**
     * Convert hue to RGB component
     */
    protected function hueToRgb(float $p, float $q, float $t): float
    {
        if ($t < 0) {
            $t += 1;
        }
        if ($t > 1) {
            $t -= 1;
        }
        if ($t < 1 / 6) {
            return $p + ($q - $p) * 6 * $t;
        }
        if ($t < 1 / 2) {
            return $q;
        }
        if ($t < 2 / 3) {
            return $p + ($q - $p) * (2 / 3 - $t) * 6;
        }

        return $p;
    }

    /**
     * Generate secondary color based on mood
     */
    protected function generateSecondaryColor(array $rgb, string $mood): string
    {
        $hsl = $this->hexToHsl($this->rgbToHex($rgb));

        // Adjust hue based on mood
        $hueShift = match ($mood) {
            'warm' => 30,
            'cool' => -30,
            'balanced' => 60,
            'complementary' => 180,
            default => 60,
        };

        return $this->hslToHex([($hsl[0] + $hueShift + 360) % 360, $hsl[1], $hsl[2]]);
    }

    /**
     * Generate accent color based on mood
     */
    protected function generateAccentColor(array $rgb, string $mood): string
    {
        $hsl = $this->hexToHsl($this->rgbToHex($rgb));

        // Accent is usually complementary or triadic
        $hueShift = match ($mood) {
            'warm' => 150,
            'cool' => 210,
            'balanced' => 180,
            'complementary' => 120,
            default => 180,
        };

        // Increase saturation for accent
        $saturation = min(1, $hsl[1] * 1.2);

        return $this->hslToHex([($hsl[0] + $hueShift + 360) % 360, $saturation, $hsl[2]]);
    }

    /**
     * Adjust brightness of RGB color
     */
    protected function adjustBrightness(array $rgb, float $amount): array
    {
        return [
            'r' => max(0, min(255, $rgb['r'] + (255 * $amount))),
            'g' => max(0, min(255, $rgb['g'] + (255 * $amount))),
            'b' => max(0, min(255, $rgb['b'] + (255 * $amount))),
        ];
    }

    /**
     * Calculate relative luminance
     */
    protected function calculateLuminance(array $rgb): float
    {
        $r = $rgb['r'] / 255;
        $g = $rgb['g'] / 255;
        $b = $rgb['b'] / 255;

        $r = $r <= 0.03928 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = $g <= 0.03928 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = $b <= 0.03928 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    /**
     * Calculate contrast ratio between two luminance values
     */
    protected function calculateContrastRatio(float $l1, float $l2): float
    {
        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);

        return ($lighter + 0.05) / ($darker + 0.05);
    }
}
