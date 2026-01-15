<?php

namespace App\Services\Templates;

abstract class BaseTemplateGenerator
{
    protected string $styleLevel;
    protected array $options;

    /**
     * Generate HTML from template
     */
    abstract public function generate(array $options, string $styleLevel): string;

    /**
     * Get classes based on style level
     */
    protected function getClasses(string $category, string $fallback = ''): string
    {
        $whitelist = config("tailwind-whitelist.{$category}.{$this->styleLevel}");
        
        if (!$whitelist) {
            return $fallback;
        }
        
        return is_array($whitelist) ? implode(' ', array_slice($whitelist, 0, 5)) : $fallback;
    }

    /**
     * Get specific classes for a component
     */
    protected function componentClasses(string $component): string
    {
        $classes = config("tailwind-whitelist.{$component}.{$this->styleLevel}", []);
        return is_array($classes) ? implode(' ', $classes) : '';
    }

    /**
     * Wrap content in a section
     */
    protected function wrapSection(string $content, array $classes = []): string
    {
        $classString = implode(' ', $classes);
        return "<section class=\"{$classString}\">\n{$content}\n</section>";
    }

    /**
     * Create a container div
     */
    protected function container(string $content): string
    {
        return "<div class=\"container mx-auto px-4 sm:px-6 lg:px-8\">\n{$content}\n</div>";
    }
}
