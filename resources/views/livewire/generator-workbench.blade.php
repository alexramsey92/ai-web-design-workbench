<div class="fixed inset-0 bg-gray-50 overflow-hidden" data-workbench data-placeholders='@json($examplePrompts)' data-generated='@json($generatedHtml)' wire:id="{{ $this->getId() }}">
    <div class="h-full flex flex-col">
        <!-- Slim header -->
        <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200 bg-white/80 text-xs">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-sm">AI Web Design Workbench</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="text-xs text-gray-600 hover:text-gray-800">Welcome</a>
                <a href="https://alexramsey92.github.io/ai-web-design-workbench/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-xs text-gray-600 hover:text-gray-800" title="View Docs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.387.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.416-4.042-1.416-.546-1.387-1.333-1.757-1.333-1.757-1.089-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.418-1.305.762-1.605-2.665-.305-5.466-1.332-5.466-5.931 0-1.31.468-2.381 1.235-3.221-.123-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.3 1.23.957-.266 1.98-.399 3-.405 1.02.006 2.043.139 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.655 1.653.243 2.874.12 3.176.77.84 1.23 1.911 1.23 3.221 0 4.61-2.807 5.624-5.48 5.921.43.369.823 1.096.823 2.214 0 1.598-.015 2.887-.015 3.281 0 .319.216.694.825.576C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                    </svg>
                    <span>Docs</span>
                </a>
            </div>
        </div>
        <!-- Main Content -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Left Panel - Controls & Editor -->
            <div class="w-1/2 flex flex-col border-r border-gray-200 bg-white">
                <!-- Input Form -->
                <div class="p-6 border-b border-gray-200 space-y-4 bg-white">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="prompt" class="block text-2xl font-semibold text-gray-700">
                                Describe what you want to build
                            </label>
                            <button 
                                wire:click="useExample"
                                type="button"
                                class="text-xs px-2 py-1 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded transition"
                            >
                                Try Example
                            </button>
                        </div>
                        <textarea 
                            wire:model="prompt"
                            id="prompt"
                            rows="3"
                            class="w-full px-3 py-2 bg-white border border-gray-300 text-lg text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400 placeholder:text-2xl"
                            @if($isGenerating) disabled @endif
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="styleLevel" class="block text-sm font-medium text-gray-700 mb-2">
                                Style Level
                            </label>
                            <select 
                                wire:model="styleLevel"
                                id="styleLevel"
                                class="w-full px-3 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @if($isGenerating) disabled @endif
                            >
                                @foreach($styleLevels as $level => $info)
                                    <option value="{{ $level }}">{{ ucfirst($level) }} - {{ $info['classes_count'] }} classes</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="pageType" class="block text-sm font-medium text-gray-700 mb-2">
                                Page Type
                            </label>
                            <select 
                                wire:model="pageType"
                                id="pageType"
                                class="w-full px-3 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @if($isGenerating) disabled @endif
                            >
                                <option value="landing">Landing Page</option>
                                <option value="business">Business Page</option>
                                <option value="portfolio">Portfolio</option>
                                <option value="blog">Blog Page</option>
                            </select>
                        </div>

                        <div>
                            <label for="maxTokens" class="block text-sm font-medium text-gray-700 mb-2">
                                Output Length
                            </label>
                            <select 
                                wire:model="maxTokens"
                                id="maxTokens"
                                class="w-full px-3 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @if($isGenerating) disabled @endif
                            >
                                @foreach($tokenOptions as $tokens => $label)
                                    <option value="{{ $tokens }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @php
                        $hasClaudeKey = !empty(config('mcp.anthropic.api_key'));
                        $byokSessionEnabled = (bool) config('mcp.byok.session_enabled');
                    @endphp
                    <details class="rounded-lg border border-gray-200 bg-gray-50/60 px-3 py-2 text-xs text-gray-700" @if(!$hasClaudeKey) open @endif>
                        <summary class="cursor-pointer font-medium text-gray-800">Claude API key</summary>
                        <div class="mt-2 space-y-3">
                            @if(!$byokSessionEnabled)
                                <div class="space-y-1 text-gray-600">
                                    <p>To enable AI generation, add your Claude API key to your .env file.</p>
                                    <p>Use <span class="font-semibold">ANTHROPIC_API_KEY=...</span> and refresh.</p>
                                </div>
                            @endif

                            <a href="https://platform.claude.com/settings/keys" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700">
                                Get your Claude API key
                                <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>

                            @if($byokSessionEnabled)
                                <div class="border-t border-gray-200 pt-3">
                                    <label for="byokApiKey" class="block font-medium text-gray-800 mb-1">
                                        Bring your own key (session-only)
                                        <span class="text-red-600 font-bold">*</span>
                                    </label>
                                    <p class="text-gray-600 mb-2">
                                        Required for generation. Temporarily stored in your session and automatically removed when your session expires. Never permanently saved.
                                    </p>
                                    <input
                                        id="byokApiKey"
                                        type="password"
                                        autocomplete="off"
                                        wire:model.blur="byokApiKey"
                                        placeholder="Paste your Claude API key sk-ant-... here"
                                        required
                                         @if($isGenerating) disabled @endif
                                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-200"
                                    />
                                    @error('byokApiKey')
                                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </details>

                    <div x-data="{ 
                        elapsedTime: 0,
                        estimatedTime: 45,
                        interval: null
                    }"
                    @generate-started.window="
                        elapsedTime = 0;
                        if (interval) clearInterval(interval);
                        interval = setInterval(() => { elapsedTime++; }, 1000);
                    "
                    @generate-finished.window="
                        if (interval) {
                            clearInterval(interval);
                            interval = null;
                        }
                        elapsedTime = 0;
                    "
                    class="space-y-2">
                        <button 
                            wire:click="generate" 
                            wire:loading.attr="disabled"
                            class="w-full px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg hover:from-blue-700 hover:to-purple-700 transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden"
                        >
                            <span wire:loading.remove wire:target="generate">Generate HTML</span>
                            <span wire:loading wire:target="generate" class="inline-flex items-center gap-2">
                                <span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                <span class="rainbow-text">Generating...</span>
                            </span>
                            
                            <!-- Progress bar -->
                            <div wire:loading wire:target="generate" class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                                <div class="h-full bg-white/60 transition-all duration-1000 ease-linear" 
                                     :style="'width: ' + Math.min(100, (elapsedTime / estimatedTime) * 100) + '%'"></div>
                            </div>
                        </button>
                        
                        <!-- Estimated time remaining -->
                        <div wire:loading wire:target="generate" class="text-center">
                            <p class="text-xs text-gray-500">Generating...</p>
                        </div>
                    </div>

                    @if($error)
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-800">{{ $error }}</p>
                        </div>
                    @endif
                </div>

                <!-- Code Editor -->
                <div class="flex-1 bg-gray-50 overflow-hidden flex flex-col">
                    <div class="p-6 pb-3">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-4">
                                <h3 class="text-sm font-semibold text-gray-700">Generated HTML</h3>
                                @if($generatedHtml)
                                    <span class="text-xs text-gray-500">{{ strlen($generatedHtml) }} characters</span>
                                @endif


                            </div>
                            <div class="flex items-center gap-2">
                                <button 
                                    wire:click="clear" 
                                    class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    <i class="fas fa-trash-alt mr-1.5"></i>Clear
                                </button>
                                <button 
                                    onclick="copyToClipboard(this)" 
                                    class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition" aria-live="polite">
                                    <i class="fas fa-copy mr-1.5"></i>Copy
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 px-6 pb-6 overflow-hidden relative">

                        <!-- Small floating draft icon (appears when a saved draft exists) -->
                        <div id="draft-overlay" class="absolute top-3 right-3 z-20 hidden items-center gap-2">
                            <button id="draft-overlay-restore" type="button" onclick="restoreDraft()"
                                class="inline-flex items-center gap-2 px-2 py-1 text-xs rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 hover:bg-emerald-100 transition"
                                title="Draft saved — click to restore">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M4 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414A2 2 0 0016.586 6L13 2.414A2 2 0 0011.586 2H4zM9 7a1 1 0 110 2H7a1 1 0 110-2h2z" />
                                </svg>
                                <span class="sr-only">Restore draft</span>
                            </button>
                            <button id="draft-overlay-dismiss" type="button" onclick="clearDraft()"
                                class="text-xs text-emerald-700 hover:text-emerald-900" title="Dismiss draft">&times;</button>
                        </div>

                        <div id="monaco-editor" wire:ignore class="w-full h-full rounded-lg overflow-hidden border-0"></div>

                        <!-- Claude Service info -->
                        @if($claudeService)
                            <div class="mt-3 p-3 bg-gray-50 border border-gray-200 rounded text-xs text-gray-700">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="font-medium text-gray-800">Claude Service</div>
                                        <div class="text-gray-500 mt-1">Request: <span class="text-gray-700">{{ \Illuminate\Support\Str::limit($claudeService['request']['prompt'] ?? ($claudeService['request']['payload']['messages'][0]['content'] ?? ''), 160) }}</span></div>
                                        <div class="text-gray-500">Model: <span class="text-gray-700">{{ $claudeService['request']['payload']['model'] ?? 'n/a' }}</span> • Max tokens: <span class="text-gray-700">{{ $claudeService['request']['payload']['max_tokens'] ?? 'n/a' }}</span></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-gray-500">Status: <span class="text-gray-700">{{ $claudeService['response']['status'] ?? 'n/a' }}</span></div>
                                        <div class="text-gray-500">Duration: <span class="text-gray-700">{{ $claudeService['response']['duration_ms'] ?? '-' }} ms</span></div>
                                    </div>
                                </div>
                                @if(!empty($claudeService['response']['body']))
                                    <div class="mt-2 text-gray-600">Response snippet: <span class="text-gray-800">{{ \Illuminate\Support\Str::limit($claudeService['response']['body'], 240) }}</span></div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Panel - Preview -->
            <div class="w-1/2 bg-gray-100 flex flex-col">
                <div class="p-4 bg-white border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700">Live Preview</h3>
                    @if($generatedHtml)
                        <button 
                            wire:click="refreshPreview"
                            class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition inline-flex items-center gap-2"
                            title="Refresh preview">
                            <i class="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                    @endif
                </div>
                <div class="flex-1 overflow-auto p-4" 
                     x-data="{ 
                        previewKey: $wire.entangle('generatedHtml'),
                        refreshPreview() {
                            const iframe = $el.querySelector('iframe');
                            if (iframe) {
                                iframe.src = iframe.src.split('?')[0] + '?t=' + Date.now();
                            }
                        }
                     }"
                     @preview-refresh.window="refreshPreview()"
                     @force-preview-refresh.window="refreshPreview()">
                    <div class="bg-white rounded-lg shadow-sm h-full overflow-auto">
                        @if($generatedHtml)
                            <iframe 
                                wire:key="preview-{{ md5($generatedHtml) }}"
                                src="{{ $this->getPreviewUrl() }}"
                                class="w-full h-full border-0"
                                sandbox="allow-same-origin allow-scripts allow-forms"
                                title="HTML Preview"
                            ></iframe>
                        @else
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <p class="text-sm">Preview will appear here</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Workbench logic moved to bundled JS (resources/js/workbench.js) -->
