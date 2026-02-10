<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandAsset extends Model
{
    protected $fillable = [
        'brand_id',
        'asset_type',
        'name',
        'description',
        'file_path',
        'mime_type',
        'file_size',
        'dimensions',
        'metadata',
        'is_primary',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'dimensions' => 'array',
            'metadata' => 'array',
            'is_primary' => 'boolean',
            'file_size' => 'integer',
            'display_order' => 'integer',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Scope to get primary assets
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to get assets by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('asset_type', $type);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSize(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2)." {$units[$unitIndex]}";
    }

    /**
     * Get asset URL (handles both storage paths and external URLs)
     */
    public function getUrl(): string
    {
        if (filter_var($this->file_path, FILTER_VALIDATE_URL)) {
            return $this->file_path;
        }

        return asset('storage/'.$this->file_path);
    }
}
