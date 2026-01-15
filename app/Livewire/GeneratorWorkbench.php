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
    public string $styleLevel = 'low';
    public string $pageType = 'landing';
    public int $maxTokens = 1024;
    public string $generatedHtml = '';
    public bool $isGenerating = false;
    public ?string $error = null;
    public bool $showPreview = false;
    public bool $hasDraft = false;

    public array $tokenOptions = [
        1024 => 'Short (1K tokens)',
        2048 => 'Medium (2K tokens)',
        4096 => 'Standard (4K tokens)',
        8192 => 'Long (8K tokens)',
    ];

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
        'maxTokens' => 'required|integer|in:1024,2048,4096,8192',
    ];

    public function generate(): void
    {
        // Increase PHP execution time for AI generation
        set_time_limit(120);
        
        $this->validate();

        $this->isGenerating = true;
        $this->error = null;
        $this->generatedHtml = '';
        
        // Dispatch event to start timer
        $this->dispatch('generate-started');
        
        // Allow UI to update before blocking call
        $this->dispatch('$refresh');

        try {
            $generator = app(HTMLGenerator::class);
            
            $this->generatedHtml = $generator->generate($this->pageType, [
                'prompt' => $this->prompt,
                'style_level' => $this->styleLevel,
                'use_semantic' => true,
                'max_tokens' => $this->maxTokens,
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
            $this->dispatch('generate-finished');
        }
    }

    public function clear(): void
    {
        $this->reset(['generatedHtml', 'error', 'showPreview']);
        session()->forget('preview_html');
        $this->dispatch('clear-draft');
    }

    public function loadDraft(string $html): void
    {
        $this->generatedHtml = $html;
        $this->showPreview = true;
        $this->hasDraft = true;
    }

    public function clearDraft(): void
    {
        $this->hasDraft = false;
    }

    /**
     * Handle unexpected toJSON calls from Livewire client-side serialization.
     * Some browser-side code may call toJSON during proxy collapse; handle gracefully.
     */
    public function toJSON($payload = null): array
    {
        Log::info('GeneratorWorkbench::toJSON called', ['payload' => $payload]);
        // Return a safe, empty payload so Livewire requests don't 500
        return ['ok' => true];
    }

    public function getPreviewUrl(): string
    {
        // Store HTML in session to avoid 414 URI Too Long errors
        session(['preview_html' => $this->generatedHtml]);
        return route('content.show') . '?t=' . time();
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
            'examplePrompts' => $this->examplePrompts,
        ]);
    }
}
