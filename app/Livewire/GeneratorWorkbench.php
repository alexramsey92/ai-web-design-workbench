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

    protected $rules = [
        'prompt' => 'required|min:10|max:1000',
        'styleLevel' => 'required|in:full,mid,low',
        'pageType' => 'required|in:landing-page,blog-post,product-page',
    ];

    public function generate(): void
    {
        $this->validate();
        
        $this->isGenerating = true;
        $this->error = null;
        $this->generatedHtml = '';

        try {
            $generator = app(HTMLGenerator::class);
            
            $this->generatedHtml = $generator->generate($this->pageType, [
                'prompt' => $this->prompt,
                'style_level' => $this->styleLevel,
                'use_semantic' => true,
            ]);

            $this->showPreview = true;
            
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
