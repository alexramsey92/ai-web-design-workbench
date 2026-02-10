# UI Component Library

**Version:** 1.0  
**Date:** February 9, 2026  
**Branch:** smbgen

## Overview

The UI Component Library provides brand-aware, reusable components that can be combined to create complete web pages. All components automatically respect brand guidelines including colors, typography, voice, and visual identity.

## Component Architecture

### Base Component Class

All components extend from a base class that provides brand integration and rendering capabilities:

```php
namespace App\Services\Components;

use App\Brand;
use App\Services\Branding\BrandManager;

abstract class BaseComponent
{
    protected Brand $brand;
    protected array $options;
    protected string $styleLevel;
    protected BrandManager $brandManager;

    public function __construct(Brand $brand, array $options = [], string $styleLevel = 'full')
    {
        $this->brand = $brand;
        $this->options = $options;
        $this->styleLevel = $styleLevel;
        $this->brandManager = app(BrandManager::class);
    }

    /**
     * Render the component as HTML
     */
    abstract public function render(): string;

    /**
     * Get component metadata (name, description, required options)
     */
    abstract public function getMetadata(): array;

    /**
     * Apply brand guidelines to component
     */
    public function applyBrandGuidelines(): self
    {
        $this->brand->load(['visualIdentity', 'voiceProfile']);
        return $this;
    }

    /**
     * Generate preview HTML
     */
    public function preview(): string
    {
        return $this->render();
    }

    /**
     * Get brand colors for component
     */
    protected function getBrandColors(): array
    {
        if (!$this->brand->visualIdentity) {
            return $this->getDefaultColors();
        }

        return $this->brand->visualIdentity->getColorsArray();
    }

    /**
     * Get default colors if no brand identity
     */
    protected function getDefaultColors(): array
    {
        return [
            'primary' => '#3B82F6',
            'secondary' => '#8B5CF6',
            'accent' => '#10B981',
        ];
    }

    /**
     * Get brand typography
     */
    protected function getBrandFonts(): array
    {
        if (!$this->brand->visualIdentity) {
            return [
                'heading' => 'Inter',
                'body' => 'Inter',
            ];
        }

        return [
            'heading' => $this->brand->visualIdentity->heading_font,
            'body' => $this->brand->visualIdentity->body_font,
        ];
    }

    /**
     * Get brand voice instructions for AI content generation
     */
    protected function getBrandVoiceInstructions(): string
    {
        if (!$this->brand->voiceProfile) {
            return '';
        }

        return $this->brand->voiceProfile->getAIInstructions();
    }
}
```

## Component Categories

### 1. Navigation Components

#### Header Component
```php
namespace App\Services\Components;

class HeaderComponent extends BaseComponent
{
    public function render(): string
    {
        $colors = $this->getBrandColors();
        $logo = $this->brand->primaryLogo?->getUrl();
        $links = $this->options['links'] ?? [];

        return view('components.navigation.header', [
            'brand' => $this->brand,
            'colors' => $colors,
            'logo' => $logo,
            'links' => $links,
            'styleLevel' => $this->styleLevel,
        ])->render();
    }

    public function getMetadata(): array
    {
        return [
            'name' => 'Header',
            'description' => 'Site header with logo and navigation',
            'category' => 'navigation',
            'required' => [],
            'optional' => ['links', 'cta_button'],
        ];
    }
}
```

#### Footer Component
Standard footer with brand information, links, and social media.

### 2. Hero Components

#### Full-Width Hero
```php
class FullWidthHeroComponent extends BaseComponent
{
    public function render(): string
    {
        return view('components.heroes.full-width', [
            'headline' => $this->options['headline'] ?? $this->brand->tagline,
            'subheadline' => $this->options['subheadline'] ?? '',
            'cta_primary' => $this->options['cta_primary'] ?? 'Get Started',
            'cta_secondary' => $this->options['cta_secondary'] ?? null,
            'background_image' => $this->options['background_image'] ?? null,
            'colors' => $this->getBrandColors(),
            'styleLevel' => $this->styleLevel,
        ])->render();
    }
}
```

#### Split Hero
Hero with content on one side and image/media on the other.

#### Centered Hero
Centered text with optional background image or gradient.

### 3. Content Block Components

#### Text Block
Simple text content block with optional heading.

#### Image Block
Image with optional caption and styling.

#### Media Object
Image + text side-by-side layout.

#### Feature Block
Icon/image, heading, description layout.

### 4. Feature Grid Components

#### Feature Grid (3-Column)
```php
class FeatureGridComponent extends BaseComponent
{
    public function render(): string
    {
        $features = $this->options['features'] ?? [];

        return view('components.features.grid', [
            'features' => $features,
            'columns' => $this->options['columns'] ?? 3,
            'colors' => $this->getBrandColors(),
            'styleLevel' => $this->styleLevel,
        ])->render();
    }

    public function getMetadata(): array
    {
        return [
            'name' => 'Feature Grid',
            'description' => 'Grid of features with icons',
            'category' => 'features',
            'required' => ['features'],
            'optional' => ['columns'],
        ];
    }
}
```

#### Icon Features
Features with icon, heading, and description.

#### Comparison Table
Side-by-side feature comparison.

### 5. Form Components

#### Contact Form
```php
class ContactFormComponent extends BaseComponent
{
    public function render(): string
    {
        return view('components.forms.contact', [
            'fields' => $this->options['fields'] ?? ['name', 'email', 'message'],
            'submit_text' => $this->options['submit_text'] ?? 'Send Message',
            'colors' => $this->getBrandColors(),
            'fonts' => $this->getBrandFonts(),
        ])->render();
    }
}
```

#### Lead Capture Form
Simplified form for lead generation.

#### Quote Request Form
Multi-step form for service quotes.

### 6. CTA Components

#### Button CTA
```php
class ButtonCTAComponent extends BaseComponent
{
    public function render(): string
    {
        return view('components.ctas.button', [
            'text' => $this->options['text'] ?? 'Get Started',
            'url' => $this->options['url'] ?? '#',
            'style' => $this->options['style'] ?? 'primary', // primary, secondary, outline
            'size' => $this->options['size'] ?? 'md',
            'colors' => $this->getBrandColors(),
        ])->render();
    }
}
```

#### Banner CTA
Full-width call-to-action banner.

#### Modal CTA
CTA that triggers a modal popup.

### 7. Social Proof Components

#### Testimonial Card
```php
class TestimonialComponent extends BaseComponent
{
    public function render(): string
    {
        return view('components.social-proof.testimonial', [
            'quote' => $this->options['quote'],
            'author' => $this->options['author'],
            'role' => $this->options['role'] ?? '',
            'company' => $this->options['company'] ?? '',
            'image' => $this->options['image'] ?? null,
            'colors' => $this->getBrandColors(),
        ])->render();
    }
}
```

#### Review Grid
Grid of customer reviews.

#### Case Study Card
Detailed case study with results.

#### Stats Section
Key metrics and statistics.

### 8. Pricing Components

#### Pricing Table
```php
class PricingTableComponent extends BaseComponent
{
    public function render(): string
    {
        return view('components.pricing.table', [
            'plans' => $this->options['plans'],
            'billing_period' => $this->options['billing_period'] ?? 'monthly',
            'colors' => $this->getBrandColors(),
            'styleLevel' => $this->styleLevel,
        ])->render();
    }
}
```

#### Pricing Card
Individual pricing plan card.

## Component Registry

Centralized registry for component discovery and instantiation:

```php
namespace App\Services\Components;

use App\Brand;

class ComponentRegistry
{
    protected array $components = [];

    public function __construct()
    {
        $this->registerDefaultComponents();
    }

    /**
     * Register a component
     */
    public function register(string $key, string $class): void
    {
        $this->components[$key] = $class;
    }

    /**
     * Get all registered components
     */
    public function all(): array
    {
        return $this->components;
    }

    /**
     * Get components by category
     */
    public function byCategory(string $category): array
    {
        return array_filter($this->components, function ($class) use ($category) {
            $instance = new $class(new Brand(), []);
            $metadata = $instance->getMetadata();
            return ($metadata['category'] ?? '') === $category;
        });
    }

    /**
     * Make a component instance
     */
    public function make(string $key, Brand $brand, array $options = [], string $styleLevel = 'full'): BaseComponent
    {
        $class = $this->components[$key] ?? null;

        if (!$class) {
            throw new \InvalidArgumentException("Component '{$key}' not found");
        }

        return new $class($brand, $options, $styleLevel);
    }

    /**
     * Register default components
     */
    protected function registerDefaultComponents(): void
    {
        // Navigation
        $this->register('header', HeaderComponent::class);
        $this->register('footer', FooterComponent::class);

        // Heroes
        $this->register('hero.full-width', FullWidthHeroComponent::class);
        $this->register('hero.split', SplitHeroComponent::class);
        $this->register('hero.centered', CenteredHeroComponent::class);

        // Features
        $this->register('features.grid', FeatureGridComponent::class);
        $this->register('features.icons', IconFeaturesComponent::class);

        // Forms
        $this->register('form.contact', ContactFormComponent::class);
        $this->register('form.lead-capture', LeadCaptureFormComponent::class);

        // CTAs
        $this->register('cta.button', ButtonCTAComponent::class);
        $this->register('cta.banner', BannerCTAComponent::class);

        // Social Proof
        $this->register('testimonial', TestimonialComponent::class);
        $this->register('reviews', ReviewGridComponent::class);
        $this->register('stats', StatsComponent::class);

        // Pricing
        $this->register('pricing.table', PricingTableComponent::class);
        $this->register('pricing.card', PricingCardComponent::class);
    }
}
```

## Component Renderer

Service for rendering components with brand application:

```php
namespace App\Services\Components;

use App\Brand;
use App\Services\Branding\BrandManager;

class ComponentRenderer
{
    public function __construct(
        protected ComponentRegistry $registry,
        protected BrandManager $brandManager
    ) {}

    /**
     * Render a single component
     */
    public function render(string $componentKey, Brand $brand, array $options = [], string $styleLevel = 'full'): string
    {
        $component = $this->registry->make($componentKey, $brand, $options, $styleLevel);
        $component->applyBrandGuidelines();

        return $component->render();
    }

    /**
     * Render multiple components as a page
     */
    public function renderPage(Brand $brand, array $components, string $styleLevel = 'full'): string
    {
        $html = [];

        foreach ($components as $componentConfig) {
            $key = $componentConfig['component'];
            $options = $componentConfig['options'] ?? [];

            $html[] = $this->render($key, $brand, $options, $styleLevel);
        }

        return $this->wrapInLayout(implode("\n\n", $html), $brand);
    }

    /**
     * Wrap components in full HTML document
     */
    protected function wrapInLayout(string $content, Brand $brand): string
    {
        $cssVariables = $this->brandManager->generateCSSVariables($brand);

        return view('layouts.brand-page', [
            'brand' => $brand,
            'content' => $content,
            'cssVariables' => $cssVariables,
        ])->render();
    }
}
```

## Usage Examples

### Single Component

```php
$registry = app(ComponentRegistry::class);
$brand = Brand::where('slug', 'acme-corp')->first();

$hero = $registry->make('hero.full-width', $brand, [
    'headline' => 'Welcome to Acme Corp',
    'subheadline' => 'Building amazing products',
    'cta_primary' => 'Get Started',
], 'full');

echo $hero->render();
```

### Complete Page

```php
$renderer = app(ComponentRenderer::class);
$brand = Brand::where('slug', 'acme-corp')->first();

$page = $renderer->renderPage($brand, [
    [
        'component' => 'hero.full-width',
        'options' => [
            'headline' => 'Welcome',
            'subheadline' => 'Your success starts here',
        ],
    ],
    [
        'component' => 'features.grid',
        'options' => [
            'features' => [
                ['icon' => 'check', 'title' => 'Feature 1', 'description' => '...'],
                ['icon' => 'star', 'title' => 'Feature 2', 'description' => '...'],
            ],
        ],
    ],
    [
        'component' => 'cta.banner',
        'options' => [
            'text' => 'Ready to get started?',
            'button_text' => 'Sign Up',
        ],
    ],
], 'full');

echo $page;
```

### With AI Generation

```php
use App\Services\AI\ClaudeService;

$claude = app(ClaudeService::class);
$brand = Brand::where('slug', 'acme-corp')->first();

// Generate component content with AI
$content = $claude->generateComponentContent('hero.full-width', [
    'brand_context' => $brand->toArray(),
    'voice_instructions' => $brand->voiceProfile?->getAIInstructions(),
]);

$hero = $registry->make('hero.full-width', $brand, $content);
echo $hero->render();
```

## Integration with Existing HTMLGenerator

The component library integrates with the existing HTMLGenerator:

```php
// In HTMLGenerator::generate()
if ($useComponents) {
    $renderer = app(ComponentRenderer::class);
    $brand = app(BrandManager::class)->getActiveBrand();

    return $renderer->renderPage($brand, $components, $options['style_level']);
}
```

## Component Blade Templates

Components use Blade templates stored in `resources/views/components/`:

```
resources/views/components/
├── navigation/
│   ├── header.blade.php
│   └── footer.blade.php
├── heroes/
│   ├── full-width.blade.php
│   ├── split.blade.php
│   └── centered.blade.php
├── features/
│   ├── grid.blade.php
│   └── icons.blade.php
├── forms/
│   ├── contact.blade.php
│   └── lead-capture.blade.php
├── ctas/
│   ├── button.blade.php
│   └── banner.blade.php
└── social-proof/
    ├── testimonial.blade.php
    ├── reviews.blade.php
    └── stats.blade.php
```

## Testing

```php
use Tests\TestCase;
use App\Brand;
use App\Services\Components\ComponentRegistry;

class ComponentTest extends TestCase
{
    public function test_hero_component_renders(): void
    {
        $brand = Brand::factory()->create();
        $registry = app(ComponentRegistry::class);

        $hero = $registry->make('hero.full-width', $brand, [
            'headline' => 'Test Headline',
        ]);

        $html = $hero->render();

        $this->assertStringContainsString('Test Headline', $html);
        $this->assertStringContainsString($brand->visualIdentity->primary_color, $html);
    }
}
```

## Next Steps

1. Create concrete component classes for each category
2. Build Blade templates for all components
3. Create Livewire component for visual component builder
4. Build component preview/showcase page
5. Integration with AI for content generation

---

See [BUSINESS_TOOLS.md](BUSINESS_TOOLS.md) for Business Model Canvas and other strategy tools.
