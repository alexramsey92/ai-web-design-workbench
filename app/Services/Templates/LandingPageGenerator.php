<?php

namespace App\Services\Templates;

class LandingPageGenerator extends BaseTemplateGenerator
{
    /**
     * Generate a complete landing page
     */
    public function generate(array $options, string $styleLevel): string
    {
        $this->options = $options;
        $this->styleLevel = $styleLevel;

        $sections = $options['sections'] ?? ['hero', 'features', 'cta'];
        $html = [];

        foreach ($sections as $section) {
            $method = 'generate' . ucfirst($section);
            if (method_exists($this, $method)) {
                $html[] = $this->$method();
            }
        }

        return implode("\n\n", $html);
    }

    /**
     * Generate hero section
     */
    protected function generateHero(): string
    {
        $companyName = $this->options['company_name'] ?? 'Your Company';
        $headline = $this->options['headline'] ?? 'Build Something Amazing';
        $subheadline = $this->options['subheadline'] ?? 'The best solution for your business needs';
        $ctaText = $this->options['cta_text'] ?? 'Get Started';
        
        $classes = match($this->styleLevel) {
            'full' => [
                'section' => 'relative bg-gradient-to-r from-primary-600 to-primary-700 py-20 lg:py-32',
                'container' => 'container mx-auto px-4 sm:px-6 lg:px-8',
                'content' => 'max-w-4xl mx-auto text-center',
                'headline' => 'text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight',
                'subheadline' => 'text-xl md:text-2xl text-white opacity-90 mb-8 leading-relaxed',
                'cta' => 'inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-lg bg-white text-primary-600 shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-200',
            ],
            'mid' => [
                'section' => 'bg-primary-600 py-16 lg:py-24',
                'container' => 'container mx-auto px-4 lg:px-8',
                'content' => 'max-w-3xl mx-auto text-center',
                'headline' => 'text-4xl md:text-5xl font-bold text-white mb-6',
                'subheadline' => 'text-xl text-white mb-8',
                'cta' => 'inline-flex px-6 py-3 text-base font-medium rounded-lg bg-white text-primary-600 hover:bg-gray-100 shadow',
            ],
            'low' => [
                'section' => 'bg-primary-600 py-12',
                'container' => 'container mx-auto px-4',
                'content' => 'max-w-2xl mx-auto text-center',
                'headline' => 'text-3xl font-bold text-white mb-4',
                'subheadline' => 'text-lg text-white mb-6',
                'cta' => 'px-6 py-3 rounded-lg bg-white text-primary-600',
            ],
        };

        return <<<HTML
<section class="{$classes['section']}">
    <div class="{$classes['container']}">
        <div class="{$classes['content']}">
            <h1 class="{$classes['headline']}">{$headline}</h1>
            <p class="{$classes['subheadline']}">{$subheadline}</p>
            <a href="#" class="{$classes['cta']}">{$ctaText}</a>
        </div>
    </div>
</section>
HTML;
    }

    /**
     * Generate features section
     */
    protected function generateFeatures(): string
    {
        $featureCount = $this->options['feature_count'] ?? 3;
        $industry = $this->options['industry'] ?? 'business';
        
        $features = [
            ['title' => 'Fast Performance', 'description' => 'Lightning-fast load times and optimized delivery'],
            ['title' => 'Secure & Reliable', 'description' => 'Enterprise-grade security and 99.9% uptime'],
            ['title' => 'Easy Integration', 'description' => 'Seamlessly integrate with your existing tools'],
        ];

        $classes = match($this->styleLevel) {
            'full' => [
                'section' => 'py-20 lg:py-24 bg-white',
                'container' => 'container mx-auto px-4 sm:px-6 lg:px-8',
                'heading' => 'text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 text-center mb-16',
                'grid' => 'grid grid-cols-1 md:grid-cols-3 gap-12',
                'card' => 'text-center',
                'icon' => 'w-16 h-16 mx-auto mb-6 text-primary-600',
                'title' => 'text-xl font-semibold text-gray-900 mb-4',
                'description' => 'text-base text-gray-600 leading-relaxed',
            ],
            'mid' => [
                'section' => 'py-16 bg-gray-50',
                'container' => 'container mx-auto px-4 lg:px-8',
                'heading' => 'text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12',
                'grid' => 'grid grid-cols-1 md:grid-cols-3 gap-8',
                'card' => 'text-center',
                'icon' => 'w-12 h-12 mx-auto mb-4 text-primary-600',
                'title' => 'text-lg font-semibold text-gray-900 mb-3',
                'description' => 'text-base text-gray-600',
            ],
            'low' => [
                'section' => 'py-12 bg-white',
                'container' => 'container mx-auto px-4',
                'heading' => 'text-2xl font-bold text-gray-900 text-center mb-8',
                'grid' => 'grid grid-cols-1 md:grid-cols-3 gap-6',
                'card' => 'text-center',
                'icon' => 'w-12 h-12 mx-auto mb-4',
                'title' => 'text-lg font-bold text-gray-900 mb-2',
                'description' => 'text-base text-gray-700',
            ],
        };

        $featureHTML = array_map(function($feature) use ($classes) {
            return <<<HTML
            <div class="{$classes['card']}">
                <svg class="{$classes['icon']}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <h3 class="{$classes['title']}">{$feature['title']}</h3>
                <p class="{$classes['description']}">{$feature['description']}</p>
            </div>
HTML;
        }, array_slice($features, 0, $featureCount));

        $featuresHTML = implode("\n", $featureHTML);

        return <<<HTML
<section class="{$classes['section']}">
    <div class="{$classes['container']}">
        <h2 class="{$classes['heading']}">Why Choose Us</h2>
        <div class="{$classes['grid']}">
{$featuresHTML}
        </div>
    </div>
</section>
HTML;
    }

    /**
     * Generate call-to-action section
     */
    protected function generateCta(): string
    {
        $headline = $this->options['cta_headline'] ?? 'Ready to Get Started?';
        $description = $this->options['cta_description'] ?? 'Join thousands of satisfied customers today';
        $buttonText = $this->options['cta_button'] ?? 'Start Free Trial';

        $classes = match($this->styleLevel) {
            'full' => [
                'section' => 'py-20 bg-primary-600',
                'container' => 'container mx-auto px-4 sm:px-6 lg:px-8',
                'content' => 'max-w-3xl mx-auto text-center',
                'headline' => 'text-4xl md:text-5xl font-bold text-white mb-6',
                'description' => 'text-xl text-white opacity-90 mb-8',
                'button' => 'inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-lg bg-white text-primary-600 shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-200',
            ],
            'mid' => [
                'section' => 'py-16 bg-primary-600',
                'container' => 'container mx-auto px-4 lg:px-8',
                'content' => 'max-w-2xl mx-auto text-center',
                'headline' => 'text-3xl md:text-4xl font-bold text-white mb-4',
                'description' => 'text-lg text-white mb-6',
                'button' => 'inline-flex px-6 py-3 text-base font-medium rounded-lg bg-white text-primary-600 hover:bg-gray-100 shadow',
            ],
            'low' => [
                'section' => 'py-12 bg-primary-600',
                'container' => 'container mx-auto px-4',
                'content' => 'max-w-xl mx-auto text-center',
                'headline' => 'text-2xl font-bold text-white mb-4',
                'description' => 'text-base text-white mb-6',
                'button' => 'px-6 py-3 rounded-lg bg-white text-primary-600',
            ],
        };

        return <<<HTML
<section class="{$classes['section']}">
    <div class="{$classes['container']}">
        <div class="{$classes['content']}">
            <h2 class="{$classes['headline']}">{$headline}</h2>
            <p class="{$classes['description']}">{$description}</p>
            <a href="#" class="{$classes['button']}">{$buttonText}</a>
        </div>
    </div>
</section>
HTML;
    }

    /**
     * Generate footer section
     */
    protected function generateFooter(): string
    {
        $companyName = $this->options['company_name'] ?? 'Your Company';
        $year = date('Y');

        $classes = match($this->styleLevel) {
            'full' => [
                'footer' => 'bg-gray-900 text-white py-12',
                'container' => 'container mx-auto px-4 sm:px-6 lg:px-8',
                'content' => 'text-center',
                'text' => 'text-gray-400',
            ],
            'mid' => [
                'footer' => 'bg-gray-900 text-white py-8',
                'container' => 'container mx-auto px-4',
                'content' => 'text-center',
                'text' => 'text-gray-400',
            ],
            'low' => [
                'footer' => 'bg-gray-900 text-white py-8',
                'container' => 'container mx-auto px-4',
                'content' => 'text-center',
                'text' => 'text-gray-400',
            ],
        };

        return <<<HTML
<footer class="{$classes['footer']}">
    <div class="{$classes['container']}">
        <div class="{$classes['content']}">
            <p class="{$classes['text']}">&copy; {$year} {$companyName}. All rights reserved.</p>
        </div>
    </div>
</footer>
HTML;
    }
}
