<?php

namespace App\Console\Commands;

use App\Services\Generation\StyleLevelManager;
use Illuminate\Console\Command;

class StyleLevelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'html:style-levels 
                            {--level= : Show details for a specific level}
                            {--classes : Show available classes for each level}';

    /**
     * The console command description.
     */
    protected $description = 'Display available styling levels and their configurations';

    protected StyleLevelManager $styleLevelManager;

    /**
     * Create a new command instance.
     */
    public function __construct(StyleLevelManager $styleLevelManager)
    {
        parent::__construct();
        $this->styleLevelManager = $styleLevelManager;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($level = $this->option('level')) {
            return $this->showLevelDetails($level);
        }

        return $this->showAllLevels();
    }

    /**
     * Show all available style levels
     */
    protected function showAllLevels(): int
    {
        $this->info('Available Styling Levels');
        $this->line('');

        $levels = $this->styleLevelManager->all();
        
        foreach ($levels as $key => $level) {
            $this->line("<fg=cyan;options=bold>{$level['name']}</> <fg=gray>({$key})</>");
            $this->line("  {$level['description']}");
            $this->line("  Class Density: <fg=yellow>{$level['class_density']}</>");
            $this->line("  Features: " . implode(', ', $level['features']));
            $this->line('');
        }

        $this->info('Use --level=<level> to see detailed class information');
        $this->info('Use --classes to see all available classes');

        return 0;
    }

    /**
     * Show details for a specific level
     */
    protected function showLevelDetails(string $levelKey): int
    {
        $level = $this->styleLevelManager->get($levelKey);
        
        if (!$level) {
            $this->error("Style level '{$levelKey}' not found");
            return 1;
        }

        $this->info($level['name']);
        $this->line($level['description']);
        $this->line('');

        if ($this->option('classes')) {
            $this->showClasses($levelKey);
        } else {
            $this->line('Features:');
            foreach ($level['features'] as $feature) {
                $this->line("  • {$feature}");
            }
            $this->line('');
            $this->info('Add --classes to see all available Tailwind classes for this level');
        }

        return 0;
    }

    /**
     * Show available classes for a level
     */
    protected function showClasses(string $level): void
    {
        $classes = $this->styleLevelManager->getClassesForLevel($level);
        
        $this->line('');
        $this->line('Available Classes by Category:');
        $this->line('');

        foreach ($classes as $category => $categoryClasses) {
            $this->line("<fg=cyan;options=bold>{$category}</>");
            
            $chunks = array_chunk($categoryClasses, 10);
            foreach ($chunks as $chunk) {
                $this->line('  ' . implode(', ', $chunk));
            }
            
            $this->line('');
        }

        $totalClasses = count($this->styleLevelManager->getFlattenedClasses($level));
        $this->info("Total classes available: {$totalClasses}");
    }
}
