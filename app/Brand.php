<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'user_id',
        'organization_id',
        'name',
        'slug',
        'tagline',
        'description',
        'industry',
        'target_audience',
        'value_proposition',
        'brand_personality',
        'is_active',
        'is_template',
    ];

    protected function casts(): array
    {
        return [
            'target_audience' => 'array',
            'brand_personality' => 'array',
            'is_active' => 'boolean',
            'is_template' => 'boolean',
        ];
    }

    // Note: User relationship removed as User model doesn't exist in this project
    // Add back when authentication is implemented

    public function visualIdentity(): HasOne
    {
        return $this->hasOne(BrandVisualIdentity::class);
    }

    public function voiceProfile(): HasOne
    {
        return $this->hasOne(BrandVoiceProfile::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(BrandAsset::class);
    }

    public function primaryLogo(): HasOne
    {
        return $this->hasOne(BrandAsset::class)
            ->where('asset_type', 'logo_primary')
            ->where('is_primary', true);
    }

    /**
     * Scope to get active brands only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get template brands
     */
    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
