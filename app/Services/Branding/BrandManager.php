<?php

namespace App\Services\Branding;

use App\Brand;
use App\BrandVisualIdentity;
use App\BrandVoiceProfile;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class BrandManager
{
    protected const SESSION_KEY = 'active_brand_id';

    /**
     * Create a new brand with complete profile
     */
    public function createBrand(array $data): Brand
    {
        // Ensure slug is set
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Create the brand
        $brand = Brand::create([
            'user_id' => $data['user_id'] ?? auth()->id(),
            'organization_id' => $data['organization_id'] ?? null,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'tagline' => $data['tagline'] ?? null,
            'description' => $data['description'] ?? null,
            'industry' => $data['industry'] ?? null,
            'target_audience' => $data['target_audience'] ?? [],
            'value_proposition' => $data['value_proposition'] ?? null,
            'brand_personality' => $data['brand_personality'] ?? [],
            'is_active' => $data['is_active'] ?? true,
            'is_template' => $data['is_template'] ?? false,
        ]);

        // Create visual identity if provided
        if (!empty($data['visual_identity'])) {
            $this->createOrUpdateVisualIdentity($brand, $data['visual_identity']);
        }

        // Create voice profile if provided
        if (!empty($data['voice_profile'])) {
            $this->createOrUpdateVoiceProfile($brand, $data['voice_profile']);
        }

        return $brand->fresh(['visualIdentity', 'voiceProfile']);
    }

    /**
     * Update brand profile
     */
    public function updateBrand(Brand $brand, array $data): Brand
    {
        // Update brand attributes
        $brand->update(array_intersect_key($data, array_flip([
            'name',
            'slug',
            'tagline',
            'description',
            'industry',
            'target_audience',
            'value_proposition',
            'brand_personality',
            'is_active',
        ])));

        // Update visual identity if provided
        if (isset($data['visual_identity'])) {
            $this->createOrUpdateVisualIdentity($brand, $data['visual_identity']);
        }

        // Update voice profile if provided
        if (isset($data['voice_profile'])) {
            $this->createOrUpdateVoiceProfile($brand, $data['voice_profile']);
        }

        return $brand->fresh(['visualIdentity', 'voiceProfile']);
    }

    /**
     * Get active brand for current user/context
     */
    public function getActiveBrand(): ?Brand
    {
        $brandId = Session::get(self::SESSION_KEY);

        if (!$brandId) {
            // Try to get user's first brand
            $brand = Brand::where('user_id', auth()->id())
                ->where('is_active', true)
                ->first();

            if ($brand) {
                $this->switchBrand($brand);
            }

            return $brand;
        }

        return Brand::with(['visualIdentity', 'voiceProfile'])
            ->find($brandId);
    }

    /**
     * Switch active brand (multi-brand context)
     */
    public function switchBrand(Brand $brand): void
    {
        Session::put(self::SESSION_KEY, $brand->id);
    }

    /**
     * Clear active brand
     */
    public function clearActiveBrand(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Validate content against brand guidelines
     */
    public function validateContent(Brand $brand, string $content): array
    {
        $issues = [];
        $score = 100;

        // Load relationships
        $brand->load(['visualIdentity', 'voiceProfile']);

        // Check if brand colors are present in content
        if ($brand->visualIdentity) {
            $colorIssues = $this->validateBrandColors($brand->visualIdentity, $content);
            if (!empty($colorIssues)) {
                $issues['colors'] = $colorIssues;
                $score -= count($colorIssues) * 5;
            }
        }

        // Check brand voice compliance (if voice profile exists)
        if ($brand->voiceProfile) {
            $voiceIssues = $this->validateBrandVoice($brand->voiceProfile, $content);
            if (!empty($voiceIssues)) {
                $issues['voice'] = $voiceIssues;
                $score -= count($voiceIssues) * 10;
            }
        }

        return [
            'score' => max(0, $score),
            'issues' => $issues,
            'status' => $score >= 80 ? 'pass' : ($score >= 60 ? 'warning' : 'fail'),
        ];
    }

    /**
     * Calculate brand consistency score
     */
    public function calculateConsistencyScore(Brand $brand, string $content): int
    {
        $validation = $this->validateContent($brand, $content);

        return $validation['score'];
    }

    /**
     * Generate Tailwind config from brand colors
     */
    public function generateTailwindConfig(Brand $brand): array
    {
        $brand->load('visualIdentity');

        if (!$brand->visualIdentity) {
            return [];
        }

        return $brand->visualIdentity->toTailwindConfig();
    }

    /**
     * Generate CSS variables from brand identity
     */
    public function generateCSSVariables(Brand $brand): string
    {
        $brand->load('visualIdentity');

        if (!$brand->visualIdentity) {
            return '';
        }

        $visual = $brand->visualIdentity;
        $css = ":root {\n";

        // Colors
        $css .= "  --brand-primary: {$visual->primary_color};\n";
        if ($visual->secondary_color) {
            $css .= "  --brand-secondary: {$visual->secondary_color};\n";
        }
        if ($visual->accent_color) {
            $css .= "  --brand-accent: {$visual->accent_color};\n";
        }
        $css .= "  --brand-success: {$visual->success_color};\n";
        $css .= "  --brand-warning: {$visual->warning_color};\n";
        $css .= "  --brand-error: {$visual->error_color};\n";

        // Typography
        $css .= "  --font-heading: '{$visual->heading_font}', sans-serif;\n";
        $css .= "  --font-body: '{$visual->body_font}', sans-serif;\n";
        $css .= "  --font-code: '{$visual->code_font}', monospace;\n";

        // Spacing
        $css .= "  --spacing-unit: {$visual->spacing_unit}px;\n";

        // Border radius
        $css .= "  --radius-sm: {$visual->border_radius_sm}px;\n";
        $css .= "  --radius-md: {$visual->border_radius_md}px;\n";
        $css .= "  --radius-lg: {$visual->border_radius_lg}px;\n";

        $css .= "}\n";

        return $css;
    }

    /**
     * Apply brand to HTML content
     */
    public function applyBrandToHTML(Brand $brand, string $html): string
    {
        $brand->load(['visualIdentity', 'voiceProfile']);

        // Replace generic color classes with brand colors
        if ($brand->visualIdentity) {
            $html = $this->applyBrandColors($html, $brand->visualIdentity);
        }

        // Apply brand-specific fonts
        if ($brand->visualIdentity) {
            $html = $this->applyBrandTypography($html, $brand->visualIdentity);
        }

        return $html;
    }

    /**
     * Create or update visual identity
     */
    protected function createOrUpdateVisualIdentity(Brand $brand, array $data): BrandVisualIdentity
    {
        return $brand->visualIdentity()->updateOrCreate(
            ['brand_id' => $brand->id],
            $data
        );
    }

    /**
     * Create or update voice profile
     */
    protected function createOrUpdateVoiceProfile(Brand $brand, array $data): BrandVoiceProfile
    {
        return $brand->voiceProfile()->updateOrCreate(
            ['brand_id' => $brand->id],
            $data
        );
    }

    /**
     * Validate brand colors in content
     */
    protected function validateBrandColors(BrandVisualIdentity $visual, string $content): array
    {
        $issues = [];

        // Check if primary color is present
        $primaryColor = strtolower($visual->primary_color);
        if (stripos($content, $primaryColor) === false && stripos($content, 'bg-primary') === false) {
            $issues[] = 'Primary brand color not used in content';
        }

        // Check for non-brand colors (simplified check)
        // This would be more sophisticated in production

        return $issues;
    }

    /**
     * Validate brand voice in content
     */
    protected function validateBrandVoice(BrandVoiceProfile $voice, string $content): array
    {
        $issues = [];

        // Check for avoided terms
        if (!empty($voice->avoid_terms)) {
            foreach ($voice->avoid_terms as $term) {
                if (stripos($content, $term) !== false) {
                    $issues[] = "Content contains avoided term: '{$term}'";
                }
            }
        }

        // Check for contractions compliance
        $hasContractions = preg_match("/\b(don't|can't|won't|shouldn't|wouldn't)/i", $content);
        if ($hasContractions && !$voice->use_contractions) {
            $issues[] = 'Content uses contractions, which is inconsistent with brand voice';
        }

        return $issues;
    }

    /**
     * Apply brand colors to HTML
     */
    protected function applyBrandColors(string $html, BrandVisualIdentity $visual): string
    {
        // Replace generic Tailwind color classes with brand colors
        // This is a simplified version - production would be more sophisticated

        $replacements = [
            'bg-blue-600' => "bg-[{$visual->primary_color}]",
            'text-blue-600' => "text-[{$visual->primary_color}]",
            'border-blue-600' => "border-[{$visual->primary_color}]",
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $html);
    }

    /**
     * Apply brand typography to HTML
     */
    protected function applyBrandTypography(string $html, BrandVisualIdentity $visual): string
    {
        // Inject font imports if not already present
        $fontImports = '';

        if ($visual->heading_font_url) {
            $fontImports .= "<link rel=\"stylesheet\" href=\"{$visual->heading_font_url}\">\n";
        }

        if ($visual->body_font_url && $visual->body_font_url !== $visual->heading_font_url) {
            $fontImports .= "<link rel=\"stylesheet\" href=\"{$visual->body_font_url}\">\n";
        }

        // Inject before closing head tag
        if (!empty($fontImports) && stripos($html, '</head>') !== false) {
            $html = str_ireplace('</head>', $fontImports.'</head>', $html);
        }

        return $html;
    }

    /**
     * Get brand for user by slug or ID
     */
    public function findBrand(string|int $identifier, ?int $userId = null): ?Brand
    {
        $query = Brand::query()->with(['visualIdentity', 'voiceProfile']);

        if (is_numeric($identifier)) {
            $query->where('id', $identifier);
        } else {
            $query->where('slug', $identifier);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->first();
    }

    /**
     * Get all brands for user
     */
    public function getUserBrands(?int $userId = null): \Illuminate\Database\Eloquent\Collection
    {
        $userId = $userId ?? auth()->id();

        return Brand::with(['visualIdentity', 'voiceProfile'])
            ->where('user_id', $userId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Duplicate a brand
     */
    public function duplicateBrand(Brand $brand, array $overrides = []): Brand
    {
        $newBrand = $brand->replicate();
        $newBrand->name = $overrides['name'] ?? $brand->name.' (Copy)';
        $newBrand->slug = $overrides['slug'] ?? Str::slug($newBrand->name);
        $newBrand->is_template = false;
        $newBrand->save();

        // Duplicate visual identity
        if ($brand->visualIdentity) {
            $newVisual = $brand->visualIdentity->replicate();
            $newVisual->brand_id = $newBrand->id;
            $newVisual->save();
        }

        // Duplicate voice profile
        if ($brand->voiceProfile) {
            $newVoice = $brand->voiceProfile->replicate();
            $newVoice->brand_id = $newBrand->id;
            $newVoice->save();
        }

        // Duplicate assets
        foreach ($brand->assets as $asset) {
            $newAsset = $asset->replicate();
            $newAsset->brand_id = $newBrand->id;
            $newAsset->save();
        }

        return $newBrand->fresh(['visualIdentity', 'voiceProfile', 'assets']);
    }
}
