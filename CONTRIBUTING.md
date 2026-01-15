# Contributing to AI Web Design Workbench

Thank you for considering contributing! This project aims to help developers rapidly generate production-ready HTML, and we welcome contributions from the community.

## How to Contribute

### Reporting Bugs

1. Check if the bug has already been reported in Issues
2. If not, create a new issue with:
   - Clear title and description
   - Steps to reproduce
   - Expected vs actual behavior
   - Your environment (PHP version, OS, etc.)

### Suggesting Features

1. Check if the feature has already been suggested
2. Create a new issue with:
   - Clear description of the feature
   - Use cases and benefits
   - Potential implementation approach (optional)

### Code Contributions

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make your changes**
   - Follow existing code style
   - Add tests if applicable
   - Update documentation

4. **Test your changes**
   ```bash
   composer test
   ```

5. **Commit with clear messages**
   ```bash
   git commit -m "Add feature: description of what you added"
   ```

6. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Create a Pull Request**

## Development Setup

```bash
# Clone your fork
git clone https://github.com/your-username/ai-web-design-workbench.git
cd ai-web-design-workbench

# Install dependencies
composer install
npm install

# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

## Code Style

- Follow PSR-12 coding standards
- Use Laravel best practices
- Write clear, self-documenting code
- Add comments for complex logic

## Adding New Templates

To add a new page template:

1. Create a new generator class in `app/Services/Templates/`
2. Extend `BaseTemplateGenerator`
3. Implement the `generate()` method
4. Add configuration in `config/mcp.php` if needed
5. Update documentation

Example:

```php
<?php

namespace App\Services\Templates;

class BlogGenerator extends BaseTemplateGenerator
{
    public function generate(array $options, string $styleLevel): string
    {
        // Implementation
    }
}
```

## Extending the Whitelist

When adding new Tailwind classes to `config/tailwind-whitelist.php`:

- Only add classes that have been tested in production
- Organize by category
- Include classes for all three style levels (full, mid, low)
- Document why specific classes are included

## Testing

Run tests before submitting:

```bash
composer test
```

## Documentation

- Update README.md for user-facing changes
- Add inline code documentation
- Include examples for new features

## Questions?

Feel free to open an issue for any questions about contributing.

Thank you for helping make AI Web Design Workbench better! 🚀
