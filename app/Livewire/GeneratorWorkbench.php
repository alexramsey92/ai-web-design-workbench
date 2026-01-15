<?php

namespace App\Livewire;

use App\Services\AI\AnthropicClient;
use App\Services\Generation\HTMLGenerator;
use App\Services\Generation\StyleLevelManager;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class GeneratorWorkbench extends Component
{
    public string $prompt = '';
    public string $styleLevel = 'full';
    public string $pageType = 'landing';
    public string $generatedHtml = '';
    public bool $isGenerating = false;
    public ?string $error = null;
    public bool $showPreview = false;
    public int $elapsedTime = 0;
    public int $estimatedTime = 45;

    public array $examplePrompts = [
        'An artisan coffee roastery in Portland that sources single-origin beans directly from farmers',
        'A mindfulness app helping busy professionals reduce stress through 5-minute guided meditations',
        'A vintage vinyl record shop with listening stations and rare collector editions',
        'A plant-based meal prep service delivering fresh, chef-crafted meals across Austin',
        'An AI-powered fitness coach that creates personalized workout plans and tracks progress',
        'A boutique glamping retreat in the mountains with stargazing decks and gourmet dining',
    ];

    protected $rules = [
        'prompt' => 'required|min:10|max:1000|string',
        'styleLevel' => 'required|in:full,mid,low',
        'pageType' => 'required|in:landing,business,portfolio,blog',
    ];

    public function generate(): void
    {
        // Increase PHP execution time for AI generation
        set_time_limit(120);
        
        $this->validate();

        $this->isGenerating = true;
        $this->error = null;
        $this->generatedHtml = '';
        $this->elapsedTime = 0;
        
        // Allow UI to update before blocking call
        $this->dispatch('$refresh');

        try {
            $generator = app(HTMLGenerator::class);
            
            $this->generatedHtml = $generator->generate($this->pageType, [
                'prompt' => $this->prompt,
                'style_level' => $this->styleLevel,
                'use_semantic' => true,
            ]);

            $this->showPreview = true;
            
            // Dispatch event for Monaco Editor
            $this->dispatch('html-generated', html: $this->generatedHtml);
            
        } catch (\Exception $e) {
            Log::error('HTML Generation failed', [
                'error' => $e->getMessage(),
                'prompt' => $this->prompt,
            ]);
            
            $this->error = 'Generation failed: ' . $e->getMessage();
        } finally {
            $this->isGenerating = false;
            $this->elapsedTime = 0;
        }
    }

    public function incrementTimer(): void
    {
        if ($this->isGenerating) {
            $this->elapsedTime++;
        }
    }

    public function clear(): void
    {
        $this->reset(['generatedHtml', 'error', 'showPreview']);
    }

    public function useExample(): void
    {
        $this->prompt = $this->examplePrompts[array_rand($this->examplePrompts)];
    }

    public function render()
    {
        $styleLevelManager = app(StyleLevelManager::class);
        $styleLevels = [];
        
        foreach ($styleLevelManager->all() as $level => $info) {
            $classes = $styleLevelManager->getFlattenedClasses($level);
            $styleLevels[$level] = [
                'name' => $info['name'],
                'description' => $info['description'],
                'classes_count' => count($classes),
            ];
        }
        
        return view('livewire.generator-workbench', [
            'styleLevels' => $styleLevels,
        ]);
    }
}
