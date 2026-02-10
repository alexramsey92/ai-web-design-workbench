# Brand Guideline System

**Version:** 1.0  
**Date:** February 9, 2026  
**Branch:** smbgen

## Overview

The Brand Guideline System is the foundational layer that ensures all generated content, components, and marketing materials maintain brand consistency across the platform.

## Core Concepts

### Brand Profile
A complete brand identity package including:
- Visual identity (colors, typography, logos)
- Brand voice & tone
- Target audience definition
- Value proposition
- Industry & positioning

### Brand Consistency Score
An automated scoring system (0-100) that evaluates:
- Color usage compliance
- Typography consistency
- Voice & tone adherence
- Component usage patterns
- Content alignment with brand values

### Multi-Brand Support
- Users can manage multiple brands (consultant/agency use case)
- Brand switching context
- Brand isolation (data separation)
- Default brand per user

## Database Schema

### brands
Primary brand profile storage.

```sql
CREATE TABLE brands (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL COMMENT 'Owner - NULL for system brands',
    organization_id BIGINT UNSIGNED NULL COMMENT 'For multi-tenant setups',
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    tagline VARCHAR(500) NULL,
    description TEXT NULL,
    industry VARCHAR(100) NULL,
    target_audience TEXT NULL COMMENT 'JSON array of audience segments',
    value_proposition TEXT NULL,
    brand_personality JSON NULL COMMENT 'Personality traits, values',
    is_active BOOLEAN DEFAULT TRUE,
    is_template BOOLEAN DEFAULT FALSE COMMENT 'Template brands for quick start',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_user (user_id),
    INDEX idx_slug (slug),
    INDEX idx_active (is_active)
);
```

### brand_visual_identity
Colors, typography, spacing, and visual design elements.

```sql
CREATE TABLE brand_visual_identity (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    brand_id BIGINT UNSIGNED NOT NULL,
    
    -- Colors (hex values)
    primary_color VARCHAR(7) NOT NULL DEFAULT '#3B82F6',
    secondary_color VARCHAR(7) NULL,
    accent_color VARCHAR(7) NULL,
    success_color VARCHAR(7) DEFAULT '#10B981',
    warning_color VARCHAR(7) DEFAULT '#F59E0B',
    error_color VARCHAR(7) DEFAULT '#EF4444',
    neutral_50 VARCHAR(7) DEFAULT '#F9FAFB',
    neutral_100 VARCHAR(7) DEFAULT '#F3F4F6',
    neutral_900 VARCHAR(7) DEFAULT '#111827',
    
    -- Typography
    heading_font VARCHAR(255) DEFAULT 'Inter',
    heading_font_url TEXT NULL COMMENT 'Google Fonts URL or CDN',
    body_font VARCHAR(255) DEFAULT 'Inter',
    body_font_url TEXT NULL,
    code_font VARCHAR(255) DEFAULT 'JetBrains Mono',
    
    -- Spacing & Layout
    spacing_unit INT DEFAULT 4 COMMENT 'Base spacing unit in px',
    border_radius_sm INT DEFAULT 4,
    border_radius_md INT DEFAULT 8,
    border_radius_lg INT DEFAULT 12,
    
    -- Shadows & Effects
    use_shadows BOOLEAN DEFAULT TRUE,
    use_gradients BOOLEAN DEFAULT TRUE,
    use_animations BOOLEAN DEFAULT TRUE,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE,
    UNIQUE KEY unique_brand_visual (brand_id)
);
```

### brand_voice_profiles
Brand voice, tone, and writing style guidelines.

```sql
CREATE TABLE brand_voice_profiles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    brand_id BIGINT UNSIGNED NOT NULL,
    
    -- Voice Characteristics
    tone ENUM('professional', 'casual', 'friendly', 'authoritative', 'playful', 'serious', 'inspirational') DEFAULT 'professional',
    formality ENUM('very_formal', 'formal', 'neutral', 'casual', 'very_casual') DEFAULT 'neutral',
    enthusiasm ENUM('low', 'moderate', 'high') DEFAULT 'moderate',
    
    -- Writing Style
    preferred_person ENUM('first', 'second', 'third', 'mixed') DEFAULT 'second' COMMENT 'I/We, You, They',
    sentence_length ENUM('short', 'medium', 'long', 'varied') DEFAULT 'varied',
    use_contractions BOOLEAN DEFAULT TRUE,
    use_emojis BOOLEAN DEFAULT FALSE,
    use_technical_jargon BOOLEAN DEFAULT FALSE,
    
    -- Vocabulary
    preferred_terms JSON NULL COMMENT 'Array of preferred words/phrases',
    avoid_terms JSON NULL COMMENT 'Array of words to avoid',
    brand_specific_terms JSON NULL COMMENT 'Product names, taglines, etc.',
    
    -- Messaging
    key_messages TEXT NULL COMMENT 'Core messages to reinforce',
    value_props JSON NULL COMMENT 'Key value propositions',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE,
    UNIQUE KEY unique_brand_voice (brand_id)
);
```

### brand_assets
Logos, images, fonts, and other brand assets.

```sql
CREATE TABLE brand_assets (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    brand_id BIGINT UNSIGNED NOT NULL,
    asset_type ENUM('logo_primary', 'logo_secondary', 'logo_icon', 'image', 'font', 'icon', 'favicon', 'other') NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    file_path VARCHAR(500) NOT NULL COMMENT 'Storage path or URL',
    mime_type VARCHAR(100) NULL,
    file_size INT NULL COMMENT 'In bytes',
    dimensions JSON NULL COMMENT '{width: 100, height: 100}',
    metadata JSON NULL COMMENT 'Additional asset-specific data',
    is_primary BOOLEAN DEFAULT FALSE COMMENT 'Primary asset for this type',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE,
    INDEX idx_brand_type (brand_id, asset_type)
);
```

### brand_templates
Pre-configured brand templates for quick start.

```sql
CREATE TABLE brand_templates (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULL,
    industry VARCHAR(100) NULL,
    preview_image VARCHAR(500) NULL,
    config JSON NOT NULL COMMENT 'Complete brand configuration',
    is_active BOOLEAN DEFAULT TRUE,
    usage_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_industry (industry),
    INDEX idx_active (is_active)
);
```

## Brand Manager Service

### Core Methods

```php
namespace App\Services\Branding;

use App\Models\Brand;
use App\Models\BrandVisualIdentity;
use App\Models\BrandVoiceProfile;

class BrandManager
{
    /**
     * Create a new brand with complete profile
     */
    public function createBrand(array $data): Brand;
    
    /**
     * Update brand profile
     */
    public function updateBrand(Brand $brand, array $data): Brand;
    
    /**
     * Get active brand for current user/context
     */
    public function getActiveBrand(): ?Brand;
    
    /**
     * Switch active brand (multi-brand context)
     */
    public function switchBrand(Brand $brand): void;
    
    /**
     * Validate content against brand guidelines
     */
    public function validateContent(Brand $brand, string $content): array;
    
    /**
     * Calculate brand consistency score
     */
    public function calculateConsistencyScore(Brand $brand, string $content): int;
    
    /**
     * Generate Tailwind config from brand colors
     */
    public function generateTailwindConfig(Brand $brand): array;
    
    /**
     * Generate CSS variables from brand identity
     */
    public function generateCSSVariables(Brand $brand): string;
    
    /**
     * Apply brand to HTML content
     */
    public function applyBrandToHTML(Brand $brand, string $html): string;
}
```

## Brand Validator Service

Validates brand compliance across content.

```php
namespace App\Services\Branding;

class BrandValidator
{
    /**
     * Validate color usage
     */
    public function validateColors(Brand $brand, string $html): array;
    
    /**
     * Validate typography usage
     */
    public function validateTypography(Brand $brand, string $html): array;
    
    /**
     * Validate brand voice in text content
     */
    public function validateVoice(Brand $brand, string $text): array;
    
    /**
     * Validate accessibility compliance
     */
    public function validateAccessibility(string $html): array;
    
    /**
     * Get comprehensive validation report
     */
    public function getValidationReport(Brand $brand, string $html, string $text): array;
}
```

## Color Scheme Generator

AI-powered color palette generation.

```php
namespace App\Services\Branding;

class ColorSchemeGenerator
{
    /**
     * Generate complementary color palette from primary color
     */
    public function generatePalette(string $primaryColor, string $mood = 'balanced'): array;
    
    /**
     * Suggest colors based on industry
     */
    public function suggestByIndustry(string $industry): array;
    
    /**
     * Validate color contrast for accessibility
     */
    public function validateContrast(string $foreground, string $background): bool;
    
    /**
     * Generate color variations (shades/tints)
     */
    public function generateVariations(string $baseColor): array;
}
```

## Brand Templates

### Pre-configured Templates

```php
// Tech Startup
[
    'name' => 'Tech Startup',
    'industry' => 'technology',
    'colors' => [
        'primary' => '#3B82F6',   // Blue
        'secondary' => '#8B5CF6', // Purple
        'accent' => '#10B981',    // Green
    ],
    'typography' => [
        'heading' => 'Inter',
        'body' => 'Inter',
    ],
    'voice' => [
        'tone' => 'professional',
        'formality' => 'neutral',
        'enthusiasm' => 'high',
    ],
];

// Local Services
[
    'name' => 'Local Services',
    'industry' => 'local_services',
    'colors' => [
        'primary' => '#F59E0B',   // Orange
        'secondary' => '#3B82F6', // Blue
        'accent' => '#10B981',    // Green
    ],
    'typography' => [
        'heading' => 'Poppins',
        'body' => 'Open Sans',
    ],
    'voice' => [
        'tone' => 'friendly',
        'formality' => 'casual',
        'enthusiasm' => 'moderate',
    ],
];

// Professional Services
[
    'name' => 'Professional Services',
    'industry' => 'professional_services',
    'colors' => [
        'primary' => '#1E40AF',   // Dark Blue
        'secondary' => '#64748B', // Slate
        'accent' => '#0EA5E9',    // Light Blue
    ],
    'typography' => [
        'heading' => 'Merriweather',
        'body' => 'Source Sans Pro',
    ],
    'voice' => [
        'tone' => 'authoritative',
        'formality' => 'formal',
        'enthusiasm' => 'low',
    ],
];

// Creative Agency
[
    'name' => 'Creative Agency',
    'industry' => 'creative',
    'colors' => [
        'primary' => '#EC4899',   // Pink
        'secondary' => '#8B5CF6', // Purple
        'accent' => '#F97316',    // Orange
    ],
    'typography' => [
        'heading' => 'Playfair Display',
        'body' => 'Lato',
    ],
    'voice' => [
        'tone' => 'playful',
        'formality' => 'casual',
        'enthusiasm' => 'high',
    ],
];
```

## Usage Examples

### Creating a New Brand

```php
$brandManager = app(BrandManager::class);

$brand = $brandManager->createBrand([
    'name' => 'Acme Landscaping',
    'slug' => 'acme-landscaping',
    'tagline' => 'Beautiful Lawns, Happy Homes',
    'industry' => 'local_services',
    'target_audience' => ['homeowners', 'property-managers'],
    'visual_identity' => [
        'primary_color' => '#10B981',
        'secondary_color' => '#3B82F6',
        'heading_font' => 'Poppins',
        'body_font' => 'Open Sans',
    ],
    'voice_profile' => [
        'tone' => 'friendly',
        'formality' => 'casual',
        'use_contractions' => true,
        'preferred_terms' => ['lawn care', 'outdoor spaces', 'curb appeal'],
    ],
]);
```

### Applying Brand to Generated HTML

```php
$html = '<div class="hero">
    <h1>Welcome to Our Service</h1>
    <p>We help businesses grow.</p>
</div>';

$brandedHtml = $brandManager->applyBrandToHTML($brand, $html);

// Result includes brand colors, fonts, and adjusted wording
```

### Validating Brand Consistency

```php
$validator = app(BrandValidator::class);

$report = $validator->getValidationReport($brand, $html, $textContent);

/*
[
    'score' => 87,
    'colors' => ['status' => 'pass', 'issues' => []],
    'typography' => ['status' => 'pass', 'issues' => []],
    'voice' => ['status' => 'warning', 'issues' => ['tone_mismatch' => 'Content tone is too formal']],
    'accessibility' => ['status' => 'pass', 'issues' => []],
]
*/
```

## Integration Points

### With HTMLGenerator

```php
// HTMLGenerator uses active brand automatically
$generator = app(HTMLGenerator::class);

$html = $generator->generate('landing-page', [
    'brand_id' => $brand->id, // Optional, uses active brand if not specified
    'prompt' => 'Create a service page for lawn mowing',
    'style_level' => 'full',
]);

// Generated HTML respects brand colors, fonts, and voice
```

### With Component Library

```php
$component = Component::make('hero')
    ->withBrand($brand)
    ->withContent([
        'headline' => 'Your Headline',
        'subheadline' => 'Supporting text',
    ])
    ->render();

// Component automatically uses brand styling
```

### With Content Strategy

```php
$campaign = ContentCampaign::create([
    'brand_id' => $brand->id,
    'name' => 'Spring Promotion',
]);

// All campaign content generated with brand voice
```

## API Endpoints

```php
// routes/api.php

Route::prefix('brands')->group(function () {
    Route::get('/', [BrandController::class, 'index']);
    Route::post('/', [BrandController::class, 'store']);
    Route::get('/{brand}', [BrandController::class, 'show']);
    Route::put('/{brand}', [BrandController::class, 'update']);
    Route::delete('/{brand}', [BrandController::class, 'destroy']);
    
    Route::post('/{brand}/validate', [BrandController::class, 'validate']);
    Route::get('/{brand}/tailwind-config', [BrandController::class, 'tailwindConfig']);
    Route::get('/{brand}/css-variables', [BrandController::class, 'cssVariables']);
    
    Route::get('/templates', [BrandTemplateController::class, 'index']);
    Route::post('/templates/{template}/apply', [BrandTemplateController::class, 'apply']);
});
```

## Testing Strategy

### Unit Tests
- Brand creation validation
- Color scheme generation
- Typography pairing
- Voice profile validation

### Feature Tests
- Complete brand workflow (create → apply → validate)
- Multi-brand switching
- Template application
- Asset management

### Browser Tests
- Brand builder UI
- Color picker functionality
- Font preview
- Live brand preview

## Next Steps

1. ✅ Create migrations
2. ✅ Create models
3. ✅ Build BrandManager service
4. Create BrandValidator service
5. Create ColorSchemeGenerator service
6. Build brand template seeder
7. Create Livewire component for brand builder
8. Integration with existing HTMLGenerator

---

See [COMPONENT_LIBRARY.md](COMPONENT_LIBRARY.md) for UI component integration with brand system.
