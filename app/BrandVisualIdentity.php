<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandVisualIdentity extends Model
{
    protected $table = 'brand_visual_identity';

    protected $fillable = [
        'brand_id',
        'primary_color',
        'secondary_color',
        'accent_color',
        'success_color',
        'warning_color',
        'error_color',
        'neutral_50',
        'neutral_100',
        'neutral_900',
        'heading_font',
        'heading_font_url',
        'body_font',
        'body_font_url',
        'code_font',
        'spacing_unit',
        'border_radius_sm',
        'border_radius_md',
        'border_radius_lg',
        'use_shadows',
        'use_gradients',
        'use_animations',
    ];

    protected function casts(): array
    {
        return [
            'spacing_unit' => 'integer',
            'border_radius_sm' => 'integer',
            'border_radius_md' => 'integer',
            'border_radius_lg' => 'integer',
            'use_shadows' => 'boolean',
            'use_gradients' => 'boolean',
            'use_animations' => 'boolean',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get all brand colors as an array
     */
    public function getColorsArray(): array
    {
        return [
            'primary' => $this->primary_color,
            'secondary' => $this->secondary_color,
            'accent' => $this->accent_color,
            'success' => $this->success_color,
            'warning' => $this->warning_color,
            'error' => $this->error_color,
            'neutral-50' => $this->neutral_50,
            'neutral-100' => $this->neutral_100,
            'neutral-900' => $this->neutral_900,
        ];
    }

    /**
     * Generate Tailwind config from brand colors
     */
    public function toTailwindConfig(): array
    {
        return [
            'colors' => [
                'brand' => [
                    'primary' => $this->primary_color,
                    'secondary' => $this->secondary_color ?? $this->primary_color,
                    'accent' => $this->accent_color ?? $this->primary_color,
                ],
                'success' => $this->success_color,
                'warning' => $this->warning_color,
                'error' => $this->error_color,
            ],
            'fontFamily' => [
                'heading' => [$this->heading_font, 'sans-serif'],
                'body' => [$this->body_font, 'sans-serif'],
                'mono' => [$this->code_font, 'monospace'],
            ],
            'spacing' => [
                'unit' => "{$this->spacing_unit}px",
            ],
            'borderRadius' => [
                'sm' => "{$this->border_radius_sm}px",
                'md' => "{$this->border_radius_md}px",
                'lg' => "{$this->border_radius_lg}px",
            ],
        ];
    }
}
