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
    public string $pageType = 'landing-page';
    public string $generatedHtml = '';
    public bool $isGenerating = false;
    public ?string $error = null;
    public bool $showPreview = false;

    public array $examplePrompts = [
        'A small flower company called Grace\'s Flowers located in 21756',
        'A modern SaaS platform for project management teams',
        'A local coffee shop with organic beans and cozy atmosphere',
        'A fitness coaching service specializing in weight loss',
        'A boutique hotel near the beach with ocean views',
    ];

    protected $rules = [
        'prompt' => 'required|min:10|max:1000|string',
        'styleLevel' => 'required|in:full,mid,low',
        'pageType' => 'required|in:landing,business,portfolio,blog',
    ];

    public function generate(): void
    {
        $this->validate();
        
        $this->isGenerating = true;
        $this->error = null;
        $this->generatedHtml = '';
        
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
