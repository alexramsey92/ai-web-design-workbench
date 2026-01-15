# AI Web Design Workbench

> **An IDE-first developer tool for rapidly generating production-ready HTML using AI, Tailwind CSS, and configurable styling levels.**

Bring your own AI-IDE, pull this repo down, let the guardrails and the opinion do the work to generate beautiful landing pages in minutes.

- Requires AI Provider API Key (Claude Preferred)
- Uses Tailwind CSS primarily
- Built to work within Laravel Herd environment

## ✨ Features

- 🎨 **Three Styling Levels**: Choose between full, mid, or low styling density
- 🎯 **Curated Tailwind Whitelist**: Only production-ready, tested classes
- 🤖 **Optional AI Integration**: Connect your Laravel Boost MCP server for AI-powered generation
- 📦 **Template Fallback**: Works perfectly without AI using built-in templates
- 🛠️ **CLI-First**: Generate HTML directly from your terminal/IDE
- 🎭 **Framework Agnostic**: Output clean HTML that works anywhere
- 📝 **Semantic & Accessible**: Follows HTML best practices and accessibility guidelines

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & npm (for Tailwind CSS)
- **Recommended:** [Laravel Herd](https://herd.laravel.com) (free, batteries-included PHP development environment)
- (Optional) Anthropic API key for AI-powered generation

### Recommended Setup with Laravel Herd

**Laravel Herd** is the fastest way to get started. It includes PHP, Composer, Node.js, and automatically serves your Laravel apps.

#### Windows Users
1. Download and install [Laravel Herd for Windows](https://herd.laravel.com)
2. Herd will automatically detect and serve this project
3. Access via: `http://ai-web-design-workbench.test`

#### Mac Users
1. Download and install [Laravel Herd for Mac](https://herd.laravel.com)
2. Herd will automatically detect and serve this project
3. Access via: `http://ai-web-design-workbench.test`

### Manual Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/alexramsey92/ai-web-design-workbench.git
   cd ai-web-design-workbench
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**
   
   **Windows:**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
   
   **Mac/Linux:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Build assets**
   ```bash
   npm run build
   ```

5. **(Optional) Enable AI Generation**
   
   Edit `.env` and add your Anthropic API key:
   ```env
   AI_CONTENT_GENERATION_ENABLED=true
   ANTHROPIC_API_KEY=your-api-key-here
   ```

### Basic Usage

Generate a landing page with full styling:

**Mac/Linux:**
```bash
php artisan html:generate landing-page \
  --company="ClientBridge" \
  --headline="Build Amazing Web Experiences" \
  --style=full \
  --output=output/landing-page.html
```

**Windows (PowerShell):**
```powershell
php artisan html:generate landing-page `
  --company="ClientBridge" `
  --headline="Build Amazing Web Experiences" `
  --style=full `
  --output=output/landing-page.html
```

**Windows (CMD):**
```cmd
php artisan html:generate landing-page --company="ClientBridge" --headline="Build Amazing Web Experiences" --style=full --output=output/landing-page.html
```

## 📖 Usage Guide

### Styling Levels

The workbench offers three styling levels to match your needs:

#### **Full Styling** (`--style=full`)
Maximum styling with gradients, shadows, transitions, hover effects, and full responsive variants. Perfect for marketing pages and high-impact designs.

```bash
php artisan html:generate landing-page --style=full
```

#### **Mid Styling** (`--style=mid`)
Balanced styling with essential utilities and moderate effects. Great for internal tools and clean business pages.

```bash
php artisan html:generate landing-page --style=mid
```

#### **Low Styling** (`--style=low`)
Minimal styling with only core utilities. Ideal for content-focused pages and starting points for custom designs.

```bash
php artisan html:generate landing-page --style=low
```

### View Available Styling Levels

```bash
# List all levels
php artisan html:style-levels

# Show details for a specific level
php artisan html:style-levels --level=full

# Show all available classes
php artisan html:style-levels --level=full --classes
```

### Generate Different Page Types

#### Landing Page

```bash
php artisan html:generate landing-page \
  --company="Your Company" \
  --headline="Your Headline" \
  --subheadline="Your subheadline" \
  --sections=hero,features,cta,footer \
  --style=full \
  --output=output/landing.html
```

### Advanced Options

#### Preview in Browser

Automatically open the generated HTML in your browser:

```bash
php artisan html:generate landing-page --output=output/page.html --preview
```

#### Custom Sections

Choose which sections to include:

```bash
php artisan html:generate landing-page \
  --sections=hero,features,cta \
  --output=output/custom.html
```

## 🤖 MCP Integration (Optional)

The workbench can integrate with Laravel Boost MCP for AI-powered generation. This is completely optional—the tool works great with built-in templates.

### Setting Up MCP

1. **Install Laravel Boost MCP Server**
   
   Follow the setup instructions for [Laravel Boost MCP](https://github.com/laravel-boost/mcp) or your preferred MCP server.

2. **Configure Environment**
   
   Edit your `.env` file:
   
   ```env
   MCP_ENABLED=true
   MCP_SERVER_URL=http://localhost:3000
   MCP_API_KEY=your-api-key-here  # Optional
   ```

3. **Test Connection**
   
   ```bash
   php artisan mcp:status
   ```

### How MCP Works

When MCP is enabled:
- The generator sends your requirements to the MCP server
- The server uses AI to generate HTML following your styling level
- The whitelist ensures only approved Tailwind classes are used
- Guardrails validate the output for semantic HTML and accessibility

When MCP is disabled:
- The generator uses built-in, handcrafted templates
- You get consistent, production-ready HTML instantly
- No external dependencies or API calls needed

## 🎯 Tailwind Whitelist

The curated whitelist ensures:
- ✅ Only battle-tested Tailwind classes
- ✅ Consistent design language
- ✅ No arbitrary or experimental utilities
- ✅ Optimized for production builds

The whitelist is organized by category and styling level. View the full list in [`config/tailwind-whitelist.php`](config/tailwind-whitelist.php).

## 📁 Project Structure

```
ai-web-design-workbench/
├── app/
│   ├── Console/Commands/       # CLI commands
│   ├── Services/
│   │   ├── Generation/         # HTML generation logic
│   │   ├── MCP/               # MCP client integration
│   │   └── Templates/         # Built-in template generators
│   └── Exceptions/
├── config/
│   ├── mcp.php                # MCP configuration
│   └── tailwind-whitelist.php # Curated Tailwind classes
├── output/                    # Generated HTML files
└── resources/
    ├── css/
    └── js/
```

## 🛠️ Development

### Adding New Templates

Create a new template generator in `app/Services/Templates/`:

```php
<?php

namespace App\Services\Templates;

class YourTemplateGenerator extends BaseTemplateGenerator
{
    public function generate(array $options, string $styleLevel): string
    {
        // Your template logic
    }
}
```

### Extending the Whitelist

Edit [`config/tailwind-whitelist.php`](config/tailwind-whitelist.php) to add new classes or categories:

```php
'your-category' => [
    'full' => ['class-1', 'class-2', ...],
    'mid' => ['class-1', ...],
    'low' => ['class-1'],
],
```

### Creating Custom Commands

```bash
php artisan make:command YourCommand
```

## 🤝 Contributing

Contributions are welcome! This is an open-source project designed to help developers build better web experiences faster.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 Examples

### Example 1: Simple Landing Page

```bash
php artisan html:generate landing-page \
  --company="Acme Corp" \
  --headline="Ship Faster" \
  --style=mid \
  --output=output/acme-landing.html
```

### Example 2: Full-Featured Marketing Page

```bash
php artisan html:generate landing-page \
  --company="SaaS Startup" \
  --headline="Transform Your Business" \
  --subheadline="The all-in-one platform for growth" \
  --sections=hero,features,cta,footer \
  --style=full \
  --output=output/saas-page.html \
  --preview
```

### Example 3: Minimal Content Page

```bash
php artisan html:generate landing-page \
  --headline="Our Story" \
  --style=low \
  --sections=hero,footer \
  --output=output/about.html
```

## 🎓 Use Cases

- **Rapid Prototyping**: Generate landing page mockups in seconds
- **Client Presentations**: Create polished page examples quickly
- **Learning Tool**: Study well-structured, semantic HTML
- **Starting Points**: Generate base HTML to customize further
- **A/B Testing**: Quickly create page variations
- **Content Sites**: Generate clean, accessible page templates

## ⚙️ Configuration

### Default Settings

Edit your `.env` file to set defaults:

```env
DEFAULT_STYLE_LEVEL=full
DEFAULT_PAGE_TYPE=landing-page
```

### Guardrails

Control HTML generation constraints in [`config/mcp.php`](config/mcp.php):

```php
'guardrails' => [
    'max_nesting_depth' => 10,
    'max_element_count' => 500,
    'require_semantic_html' => true,
    'require_accessible_markup' => true,
],
```

## 🐛 Troubleshooting

### MCP Connection Issues

```bash
php artisan mcp:status
```

Check:
- Is your MCP server running?
- Is the `MCP_SERVER_URL` correct?
- Do you need an `MCP_API_KEY`?

### Missing Dependencies

```bash
composer install
npm install
```

### Permission Issues

Ensure the `output/` directory is writable:

```bash
chmod -R 755 output/
```

## 📄 License

MIT License - see [LICENSE](LICENSE) for details.

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- Styled with [Tailwind CSS](https://tailwindcss.com)
- Inspired by the Laravel Boost MCP project

## 📬 Support

- Open an issue for bug reports or feature requests
- Star the repo if you find it useful!
- Share with other developers who might benefit

---

**Built by developers, for developers.** 🚀

Start generating better HTML today, right from your IDE.
