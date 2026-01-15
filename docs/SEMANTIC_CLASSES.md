# Semantic CSS Classes

The workbench now supports **semantic CSS classes** in addition to pure Tailwind utilities. This approach, inspired by the SMBGEN implementation, makes your HTML more readable and maintainable.

## Philosophy

Instead of:
```html
<section class="py-20 lg:py-32 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-center">
```

Write:
```html
<section class="hero gradient-hero">
```

## Available Classes

### Layout

- `.container` - Responsive container with padding
- `.content-block` - Centered content block (max-width)

### Sections

- `.section` - Standard section padding
- `.hero` - Hero section styling
- `.gradient-hero` - Hero with gradient background
- `.gradient-accent` - Accent gradient background
- `.cta-section` - Call-to-action section
- `.bg-accent` - Accent background color
- `.bg-brand` - Brand background color

### Typography

- `.heading` - Large heading text
- `.subheading` - Secondary heading
- `.lead` - Lead paragraph text
- `.text-brand` - Brand color text
- `.text-accent` - Accent color text
- `.prose` - Prose styling for rich content

### Buttons

- `.btn-primary` - Primary button
- `.btn-secondary` - Secondary button
- `.btn-outline` - Outline button

### Components

- `.card` - Card component
- `.feature-grid` - Feature grid layout
- `.feature-card` - Individual feature card
- `.alert`, `.alert-success`, `.alert-info`, `.alert-warning` - Alert boxes

## Style Level Variants

Each semantic class has variants for different style levels:

- Default (full): `.heading`
- Mid: `.heading-mid`
- Low: `.heading-low`

## Usage

### Generate with Semantic Classes (Default)

```bash
php artisan html:generate landing-page \
  --sections=hero,features,cta \
  --style=full \
  --output=output/page.html
```

### Toggle Pure Tailwind

Set in `.env`:
```env
USE_SEMANTIC_CLASSES=false
```

Or in config:
```php
// config/mcp.php
'use_semantic_classes' => false,
```

## Customizing Semantic Classes

Edit [`resources/css/semantic.css`](../resources/css/semantic.css) to customize the semantic class definitions:

```css
@layer components {
  .hero {
    @apply py-20 lg:py-32 text-center;
  }
  
  .btn-primary {
    @apply px-8 py-4 text-lg font-semibold rounded-lg;
    @apply bg-white text-primary-600 shadow-lg;
    @apply hover:shadow-xl hover:scale-105;
    @apply transition-all duration-200;
  }
}
```

## Benefits

✅ **More Readable HTML** - Semantic names vs long utility chains  
✅ **Easier to Maintain** - Change styling in one place  
✅ **Better DX** - Autocomplete in IDE  
✅ **Consistent Design** - Enforced design system  
✅ **Flexible** - Mix with Tailwind utilities as needed  

## Example: SMBGEN-Style Landing Page

```bash
php artisan html:generate landing-page \
  --company="My Business" \
  --headline="Built for Small Businesses" \
  --subheadline="Marketing made simple" \
  --sections=hero,problem,features,testimonials,stats,cta,footer \
  --style=full \
  --output=output/landing.html \
  --preview
```

This generates clean, semantic HTML similar to the SMBGEN example.
