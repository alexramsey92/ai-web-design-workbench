# AI Web Design Workbench

[View the demo on GitHub Pages](https://alexramsey92.github.io/ai-web-design-workbench/)

> **A universal workbench for rapidly generating production-ready HTML using AI, Tailwind CSS, and semantic design patterns.**

https://github.com/user-attachments/assets/2c8c30a7-f250-43bf-b2bd-d30f196fd4dc

A flexible tool that meets you where you work. Generate beautiful landing pages through your IDE, a browser-based code editor, or command-line interface—your choice.

- Requires AI Provider API Key (Claude Preferred)
- Uses Tailwind CSS + Semantic Classes
- Built to work within Laravel Herd environment

## 🎯 Three Ways to Work

This workbench is designed to fit **your** workflow. Choose how you want to generate content:

### 1. 🎨 **IDE Workflow** (Most Powerful)

Work directly in your favorite IDE (VS Code, PHPStorm, Cursor, etc.) with AI coding assistants.

**How it works:**
- Open this project in your IDE
- Use your AI assistant (GitHub Copilot, Cursor, etc.) to generate or modify files
- Build HTML pages directly in the project
- Copy finished files out to your destination project

**Best for:**
- Iterative design with AI assistance
- Complex customizations
- Learning and experimentation
- Full control over every detail

**Where we draw the line:** Once you move files out of this workbench, they're yours. This project provides the workspace, not the final hosting.

### 2. 🌐 **Browser Workbench** (Quick & Visual)

A split-screen code editor with live preview, accessible through your browser.

**How it works:**
- Launch the workbench: `http://ai-web-design-workbench.test/workbench`
- Describe what you want to build
- See generated HTML instantly with live preview
- Edit code in the browser with syntax highlighting
- Copy finished HTML when ready

**Best for:**
- Quick prototyping
- Visual feedback while building
- Sharing access with non-developers
- No IDE setup required

### 3. ⌨️ **CLI Commands** (Fast & Scriptable)

Generate HTML directly from your terminal.

**How it works:**
```bash
php artisan html:generate landing-page \
  --company="Your Company" \
  --headline="Your Headline" \
  --style=full \
  --output=output/page.html
```

**Best for:**
- Batch generation
- CI/CD integration
- Quick one-off pages
- Scripting and automation

---

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

5. **(Optional) Enable AI Generation with Anthropic Claude**
   
   a. **Get your Anthropic API key:**
      - Visit https://console.anthropic.com/
      - Sign up or log in to your account
      - Navigate to API Keys section
      - Create a new API key
   
   b. **Configure your environment:**
      
      Edit `.env` and add your API key:
      ```env
      AI_CONTENT_GENERATION_ENABLED=true
      ANTHROPIC_API_KEY=sk-ant-api03-xxxxx  # Your actual API key
      ```
   
   c. **Verify the connection works:**
      
      ```bash
      php artisan ai:status
      ```
      
      **Expected Output:**
      ```
      ✓ AI Content Generation is ENABLED
      ✓ Anthropic API is responding
      ```
      
      If you see "✗ Anthropic API is not responding":
      - Verify your API key is correct
      - Check you have API credits/quota available
      - Ensure your internet connection is working
   
   **Without AI:** The workbench works perfectly using built-in semantic templates. AI is completely optional!

### Basic Usage

**Option 1: Browser Workbench**
```
Navigate to: http://ai-web-design-workbench.test/workbench
```

**Option 2: CLI Generation**

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

**Option 3: IDE Workflow**
```bash
# Open project in your IDE
code .  # VS Code
# or
cursor .  # Cursor
# or
---

## 🎨 IDE Workflow Best Practices

### Setting Up Your IDE

1. **Open the project**
   ```bash
   cd ai-web-design-workbench
   code .  # or your preferred IDE
   ```

2. **Enable AI Assistant**
   - GitHub Copilot
   - Cursor AI
   - Codeium
   - Or any AI coding assistant

3. **Create output directory**
   ```bash
   mkdir -p resources/output
   ```

### Working with AI in Your IDE

**Example Prompt for Your AI Assistant:**
```
Create a landing page HTML file for a boutique coffee shop in Portland. 
Use semantic classes like .hero, .feature-grid, and .cta-button.
Include Tailwind utilities for spacing and typography.
Save to resources/output/coffee-shop.html
```

**Your AI will:**
- Generate complete, semantic HTML
- Use the project's design patterns
- Follow best practices automatically
- Create files you can immediately use

### Moving Files Out

Once you're happy with the generated HTML:

```bash
# Copy to your destination project
cp resources/output/coffee-shop.html ~/Projects/my-website/pages/

# Or open and copy manually
```

**Responsibility boundary:** Once files leave this workbench, you own them completely. This tool provides the workspace and generation power—what you do with the output is up to you.

---

## 📖storm .  # PHPStorm

# Use your AI assistant to generate files in resources/output/
# Then copy to your destination project
```

---

## 🌐 Browser Workbench Guide

The browser workbench provides a split-screen interface for rapid HTML generation.

### Accessing the Workbench

Navigate to: `http://ai-web-design-workbench.test/workbench`

### Using the Interface

1. **Describe Your Page**
   - Enter a description (e.g., "A fitness coaching service specializing in weight loss")
   - Click "Try Example" for inspiration
   - Choose your style level (Full/Mid/Low)
   - Select page type (Landing/Business/Portfolio/Blog)

2. **Generate HTML**
   - Click "Generate HTML" button
   - Watch the AI create your page in real-time
   - See live preview on the right side

3. **Edit & Refine**
   - Edit HTML directly in the code editor
   - Changes reflect instantly in the preview
   - Use semantic classes: `.hero`, `.feature-grid`, `.cta-button`

4. **Export Your Work**
   - Click "Copy Code" to copy the HTML
   - Paste into your project
   - Add to your own hosting

### Workbench Features

- **Live Preview**: See changes instantly in the right panel
- **Cycling Placeholders**: Get prompt ideas automatically
- **Syntax Highlighting**: Clean, readable code editor
- **One-Click Copy**: Export your HTML with a single click
- **Responsive Design**: Preview works on all screen sizes

---

## ⌨️ CLI Commands Guide

### Generate Landing Pages*
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

The workbench can integrate with Laravel Boost MCP for AI-powered generation. This is completely optional—the tool works great with built-in templates. Install after Herd is installed on your machine to also install Herd MCP alongside Boost MCP.

### Setting Up MCP

1. **Install Laravel Boost MCP Server**
   
   Follow the setup instructions for [Laravel Boost MCP](https://github.com/laravel/boost) or your preferred MCP server.

   Laravel Boost can be installed via Composer: `composer require laravel/boost -w --dev`
    Next, install the MCP server and coding guidelines: `php artisan boost:install`

    You will be asked a few questions, I prefer vscode and copilot but you are welcome to use whatever works for you.

2. **Configure Environment**
   
   Edit your `.env` file:
   
   ```env
   MCP_ENABLED=true
   MCP_SERVER_URL=http://localhost:3000
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
 by Method

### IDE Workflow Use Cases
- Complex, multi-page projects
- Heavy customization needed
- Learning HTML/CSS patterns
- Rapid iteration with AI feedback
- Building component libraries

### Browser Workbench Use Cases
- Quick landing page prototypes
- Client presentations
- Non-developer access
- Visual design iteration
- Teaching/demonstration

### CLI Use Cases
- Automated page generation
- Batch processing
- CI/CD integration
- Template generation
- Scripted workflows

---

## 🚀 Quickstart by Method

### Just Want to Try It? → Browser Workbench
```
1. Open: http://ai-web-design-workbench.test/workbench
2. Click "Try Example"
3. Click "Generate HTML"
4. Copy the code
```

### Want Full Control? → IDE Workflow
```bash
1. code .
2. Ask your AI: "Create a landing page for X"
3. Edit and refine
4. Copy files to your project
```

### Need Automation? → CLI Commands
```bash
php artisan html:generate landing-page \
  --company="Your Company" \
  --style=full \
  --output=output/page.html \
  --preview
```

---

## 🎯 Choosing Your Method

| Need | Best Method |
|------|-------------|
| Visual feedback | 🌐 Browser Workbench |
| AI assistance | 🎨 IDE Workflow |
| Quick prototypes | 🌐 Browser Workbench |
| Full customization | 🎨 IDE Workflow |
| Automation | ⌨️ CLI Commands |
| Learning | 🎨 IDE Workflow |
| Sharing | 🌐 Browser Workbench |
| Batch generation | ⌨️ CLI Commands |

---

## 🎓 Use Cases
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

Built quickly 🏎️ with [Laravel](https://laravel.com) and [Laravel Boost](https://github.com/laravel/boost)

- Styled with [Tailwind CSS](https://tailwindcss.com)
- Powered by [Anthropic Claude](https://www.anthropic.com)
- Made with ❤️ by [Alexander Ramsey](https://alexanderramsey.com)

## 📬 Support

- Open an issue for bug reports or feature requests
- Star the repo if you find it useful!
- Share with other developers who might benefit
- Check out more projects at [alexanderramsey.com](https://alexanderramsey.com)

---

**Built by developers, for developers.** 🚀

Start generating better HTML today, right from your IDE.
