<?php

namespace App\Console\Commands;

use App\Services\Generation\HTMLGenerator;
use App\Services\Generation\StyleLevelManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateHTML extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'html:generate 
                            {type : The type of HTML to generate (landing-page, blog, product-page)}
                            {--style=full : The style level (full, mid, low)}
                            {--output= : Output file path (optional)}
                            {--company= : Company name}
                            {--headline= : Headline text}
                            {--subheadline= : Subheadline text}
                            {--sections=* : Sections to include (hero, features, cta, problem, testimonials, stats, footer)}
                            {--preview : Preview in browser after generation}
                            {--semantic : Use semantic CSS classes (default: true)}';

    /**
     * The console command description.
     */
    protected $description = 'Generate HTML content using AI or templates';

    protected HTMLGenerator $generator;
    protected StyleLevelManager $styleLevelManager;

    /**
     * Create a new command instance.
     */
    public function __construct(HTMLGenerator $generator, StyleLevelManager $styleLevelManager)
    {
        parent::__construct();
        $this->generator = $generator;
        $this->styleLevelManager = $styleLevelManager;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->argument('type');
        $styleLevel = $this->option('style');

        // Validate style level
        if (!$this->styleLevelManager->exists($styleLevel)) {
            $this->error("Invalid style level: {$styleLevel}");
            $this->info('Available levels: ' . implode(', ', array_keys($this->styleLevelManager->all())));
            return 1;
        }

        $this->info("Generating {$type} with {$styleLevel} styling...");

        // Build options from command arguments
        $options = $this->buildOptions();

        try {
            // Generate HTML
            $html = $this->generator->generate($type, array_merge($options, [
                'style_level' => $styleLevel,
            ]));

            // Format HTML
            $formatted = $this->generator->formatHTML($html);

            // Validate HTML
            $issues = $this->generator->validateHTML($formatted);
            if (!empty($issues)) {
                $this->warn('Validation warnings:');
                foreach ($issues as $issue) {
                    $this->warn("  - {$issue}");
                }
            }

            // Output or save
            if ($outputPath = $this->option('output')) {
                $this->saveToFile($formatted, $outputPath);
            } else {
                $this->displayHTML($formatted);
            }

            $this->info('✓ HTML generated successfully!');

            // Preview option
            if ($this->option('preview') && $outputPath) {
                $this->previewInBrowser($outputPath);
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Generation failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Build options array from command input
     */
    protected function buildOptions(): array
    {
        $options = [];

        // Map command options to generation options
        $mappings = [
            'company' => 'company_name',
            'headline' => 'headline',
            'subheadline' => 'subheadline',
        ];

        foreach ($mappings as $optionName => $key) {
            if ($value = $this->option($optionName)) {
                $options[$key] = $value;
            }
        }

        // Handle sections
        if ($sections = $this->option('sections')) {
            $options['sections'] = $sections;
        }

        return $options;
    }

    /**
     * Display HTML in terminal
     */
    protected function displayHTML(string $html): void
    {
        $this->line('');
        $this->line('Generated HTML:');
        $this->line('─────────────────────────────────────────');
        $this->line($html);
        $this->line('─────────────────────────────────────────');
        $this->line('');
    }

    /**
     * Save HTML to file
     */
    protected function saveToFile(string $html, string $path): void
    {
        // Ensure directory exists
        $directory = dirname($path);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Wrap in full HTML document if it's not already
        if (!str_contains($html, '<!DOCTYPE html>')) {
            $html = $this->wrapInDocument($html);
        }

        File::put($path, $html);
        $this->info("HTML saved to: {$path}");
    }

    /**
     * Wrap HTML in a complete document
     */
    protected function wrapInDocument(string $body): string
    {$cssLink = $this->option('semantic') !== false && config('mcp.use_semantic_classes', true)
            ? '<link href="/css/app.css" rel="stylesheet">'
            : '<script src="https://cdn.tailwindcss.com"></script>';

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Page</title>
    {$cssLink}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
{$body}
</body>
</html>
HTML;
    }

    /**
     * Preview HTML in browser
     */
    protected function previewInBrowser(string $path): void
    {
        $fullPath = realpath($path);
        if ($fullPath) {
            $this->info("Opening preview in browser...");
            
            // Platform-specific open command
            $command = match(PHP_OS_FAMILY) {
                'Windows' => "start {$fullPath}",
                'Darwin' => "open {$fullPath}",
                default => "xdg-open {$fullPath}",
            };
            
            exec($command);
        }
    }
}
