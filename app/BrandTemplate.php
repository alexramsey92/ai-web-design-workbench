<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'industry',
        'preview_image',
        'config',
        'is_active',
        'usage_count',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'is_active' => 'boolean',
            'usage_count' => 'integer',
        ];
    }

    /**
     * Scope to get active templates only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get templates by industry
     */
    public function scopeForIndustry($query, string $industry)
    {
        return $query->where('industry', $industry);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Create a brand from this template
     */
    public function createBrand(array $overrides = []): Brand
    {
        $config = array_merge($this->config, $overrides);

        $brand = Brand::create([
            'name' => $config['name'] ?? $this->name,
            'slug' => $config['slug'] ?? str($config['name'] ?? $this->name)->slug(),
            'tagline' => $config['tagline'] ?? null,
            'description' => $config['description'] ?? $this->description,
            'industry' => $this->industry,
            'target_audience' => $config['target_audience'] ?? [],
            'value_proposition' => $config['value_proposition'] ?? null,
            'brand_personality' => $config['brand_personality'] ?? [],
            'is_active' => true,
        ]);

        // Create visual identity if provided
        if (!empty($config['visual_identity'])) {
            $brand->visualIdentity()->create($config['visual_identity']);
        }

        // Create voice profile if provided
        if (!empty($config['voice_profile'])) {
            $brand->voiceProfile()->create($config['voice_profile']);
        }

        $this->incrementUsage();

        return $brand;
    }
}
