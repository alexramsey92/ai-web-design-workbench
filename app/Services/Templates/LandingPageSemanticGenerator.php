<?php

namespace App\Services\Templates;

class LandingPageSemanticGenerator extends BaseTemplateGenerator
{
    /**
     * Generate a complete landing page using semantic classes
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
     * Generate hero section with semantic classes
     */
    protected function generateHero(): string
    {
        $headline = $this->options['headline'] ?? 'Build Something Amazing';
        $subheadline = $this->options['subheadline'] ?? 'The best solution for your business needs';
        $ctaText = $this->options['cta_text'] ?? 'Get Started';
        $ctaSecondary = $this->options['cta_secondary'] ?? 'Learn More';

        $heroClass = $this->getSemanticClass('hero');
        $headingClass = $this->getSemanticClass('heading');
        $subheadingClass = $this->getSemanticClass('subheading');
        $btnPrimary = $this->getSemanticClass('btn-primary');
        $btnOutline = $this->getSemanticClass('btn-outline');

        return <<<HTML
<section class="{$heroClass} gradient-hero">
  <div class="container">
    <h1 class="{$headingClass}">{$headline}</h1>
    <p class="{$subheadingClass}">{$subheadline}</p>
    <div class="flex justify-center gap-4 mt-8">
      <a href="#get-started" class="{$btnPrimary} inline-flex items-center justify-center">
        {$ctaText}
      </a>
      <a href="#features" class="{$btnOutline} inline-flex items-center justify-center">
        {$ctaSecondary}
      </a>
    </div>
  </div>
</section>
HTML;
    }

    /**
     * Generate features section with semantic classes
     */
    protected function generateFeatures(): string
    {
        $industry = $this->options['industry'] ?? 'business';
        $sectionTitle = $this->options['features_title'] ?? 'Why Choose Us';
        
        $features = $this->options['features'] ?? [
            ['title' => 'Fast Performance', 'description' => 'Lightning-fast load times and optimized delivery'],
            ['title' => 'Secure & Reliable', 'description' => 'Enterprise-grade security and 99.9% uptime'],
            ['title' => 'Easy Integration', 'description' => 'Seamlessly integrate with your existing tools'],
        ];

        $sectionClass = $this->getSemanticClass('section');
        $headingClass = $this->getSemanticClass('heading');
        $subheadingClass = $this->getSemanticClass('subheading');

        $featureHTML = array_map(function($feature) use ($subheadingClass) {
            return <<<HTML
      <div class="feature-card">
        <h3 class="{$subheadingClass}">{$feature['title']}</h3>
        <p>{$feature['description']}</p>
      </div>
HTML;
        }, $features);

        $featuresHTML = implode("\n", $featureHTML);

        return <<<HTML
<section id="features" class="{$sectionClass} bg-accent">
  <div class="container">
    <h2 class="{$headingClass}">{$sectionTitle}</h2>
    <div class="feature-grid mt-8">
{$featuresHTML}
    </div>
  </div>
</section>
HTML;
    }

    /**
     * Generate call-to-action section with semantic classes
     */
    protected function generateCta(): string
    {
        $headline = $this->options['cta_headline'] ?? 'Ready to Get Started?';
        $description = $this->options['cta_description'] ?? 'Join thousands of satisfied customers today';
        $buttonText = $this->options['cta_button'] ?? 'Start Free Trial';

        $headingClass = $this->getSemanticClass('heading');
        $subheadingClass = $this->getSemanticClass('subheading');
        $btnPrimary = $this->getSemanticClass('btn-primary');

        return <<<HTML
<section class="cta-section gradient-accent">
  <div class="container">
    <h2 class="{$headingClass}">{$headline}</h2>
    <p class="{$subheadingClass} mt-4">{$description}</p>
    <div class="flex justify-center gap-4 mt-8">
      <a href="#contact" class="{$btnPrimary} inline-flex items-center justify-center">
        {$buttonText}
      </a>
    </div>
  </div>
</section>
HTML;
    }

    /**
     * Generate problem/solution section (like SMBGEN)
     */
    protected function generateProblem(): string
    {
        $heading = $this->options['problem_heading'] ?? 'The Challenge';
        $lead = $this->options['problem_lead'] ?? 'You have better things to do';
        $problems = $this->options['problems'] ?? [
            'Expensive and time-consuming',
            'Inconsistent results',
            'Too complex to manage',
        ];
        $solution = $this->options['solution'] ?? 'We make it simple.';

        $headingClass = $this->getSemanticClass('heading');
        $sectionClass = $this->getSemanticClass('section');

        $problemList = array_map(fn($p) => "<li>{$p}</li>", $problems);
        $problemsHTML = implode("\n          ", $problemList);

        return <<<HTML
<section class="{$sectionClass}">
  <div class="container">
    <div class="content-block">
      <h2 class="{$headingClass}">{$heading}</h2>
      <p class="lead text-accent">{$lead}</p>
      <div class="prose mt-6">
        <ul>
          {$problemsHTML}
        </ul>
        <p class="mt-6"><strong>{$solution}</strong></p>
      </div>
    </div>
  </div>
</section>
HTML;
    }

    /**
     * Generate testimonials section
     */
    protected function generateTestimonials(): string
    {
        $heading = $this->options['testimonials_heading'] ?? 'What Our Customers Say';
        $testimonials = $this->options['testimonials'] ?? [
            [
                'quote' => 'This platform has transformed how we work. Highly recommended!',
                'author' => 'John Doe',
                'company' => 'Acme Corp',
            ],
        ];

        $sectionClass = $this->getSemanticClass('section');
        $headingClass = $this->getSemanticClass('heading');

        $testimonialHTML = array_map(function($t) {
            $company = isset($t['company']) ? ", {$t['company']}" : '';
            return <<<HTML
      <div class="card p-6">
        <p class="lead">"{$t['quote']}"</p>
        <p class="mt-4"><strong>— {$t['author']}</strong>{$company}</p>
      </div>
HTML;
        }, $testimonials);

        $testimonialsHTML = implode("\n", $testimonialHTML);

        return <<<HTML
<section class="{$sectionClass}">
  <div class="container">
    <div class="content-block">
      <h2 class="{$headingClass}">{$heading}</h2>
      <div class="grid md:grid-cols-1 gap-6 mt-8">
{$testimonialsHTML}
      </div>
    </div>
  </div>
</section>
HTML;
    }

    /**
     * Generate stats section
     */
    protected function generateStats(): string
    {
        $stats = $this->options['stats'] ?? [
            ['value' => '10,000+', 'label' => 'Happy Customers'],
            ['value' => '99.9%', 'label' => 'Uptime Guarantee'],
            ['value' => '24/7', 'label' => 'Support Available'],
        ];

        $sectionClass = $this->getSemanticClass('section');
        $headingClass = $this->getSemanticClass('heading');

        $statHTML = array_map(function($stat) use ($headingClass) {
            return <<<HTML
        <div class="content-block text-center">
          <h3 class="text-brand {$headingClass}">{$stat['value']}</h3>
          <p>{$stat['label']}</p>
        </div>
HTML;
        }, $stats);

        $statsHTML = implode("\n", $statHTML);

        return <<<HTML
<section class="{$sectionClass}">
  <div class="container">
    <div class="grid md:grid-cols-3 gap-8">
{$statsHTML}
    </div>
  </div>
</section>
HTML;
    }

    /**
     * Generate footer section with semantic classes
     */
    protected function generateFooter(): string
    {
        $companyName = $this->options['company_name'] ?? 'Your Company';
        $year = date('Y');

        return <<<HTML
<footer class="section bg-gray-900 text-white">
  <div class="container">
    <div class="text-center">
      <p class="text-gray-400">&copy; {$year} {$companyName}. All rights reserved.</p>
    </div>
  </div>
</footer>
HTML;
    }

    /**
     * Get semantic class name based on style level
     */
    protected function getSemanticClass(string $baseClass): string
    {
        return match($this->styleLevel) {
            'mid' => $baseClass . '-mid',
            'low' => $baseClass . '-low',
            default => $baseClass,
        };
    }
}
