<div class="min-h-screen bg-gray-50">
    <div class="h-screen flex flex-col">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4 flex-shrink-0">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">HTML Generator Workbench</h1>
                <div class="flex items-center gap-3">
                    @if($generatedHtml)
                        <button 
                            wire:click="clear" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Clear
                        </button>
                        <button 
                            onclick="copyToClipboard()" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-copy mr-2"></i>Copy Code
                        </button>
                    @endif
                </div>
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
                            <label for="prompt" class="block text-sm font-medium text-gray-700">
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
                        <div x-data="{ 
                            placeholders: @js($examplePrompts), 
                            currentIndex: 0,
                            init() {
                                setInterval(() => {
                                    this.currentIndex = (this.currentIndex + 1) % this.placeholders.length;
                                }, 3000);
                            }
                        }">
                            <textarea 
                                wire:model="prompt"
                                id="prompt"
                                rows="3"
                                class="w-full px-3 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
                                x-bind:placeholder="placeholders[currentIndex]"
                                @if($isGenerating) disabled @endif
                            ></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
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
                    </div>

                    <div x-data="{ 
                        generating: @entangle('isGenerating'),
                        elapsedTime: 0,
                        estimatedTime: 45,
                        interval: null,
                        startTimer() {
                            this.elapsedTime = 0;
                            this.interval = setInterval(() => {
                                if (this.generating) {
                                    this.elapsedTime++;
                                } else {
                                    this.stopTimer();
                                }
                            }, 1000);
                        },
                        stopTimer() {
                            if (this.interval) {
                                clearInterval(this.interval);
                                this.interval = null;
                            }
                            this.elapsedTime = 0;
                        }
                    }"
                    x-init="$watch('generating', value => { if (value) startTimer(); else stopTimer(); })"
                    class="space-y-2">
                        <button 
                            wire:click="generate" 
                            wire:loading.attr="disabled"
                            class="w-full px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg hover:from-blue-700 hover:to-purple-700 transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden"
                        >
                            <span wire:loading.remove wire:target="generate">Generate HTML</span>
                            <span wire:loading wire:target="generate" class="inline-flex items-center gap-2">
                                <span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                <span class="rainbow-text" x-text="'Generating... ' + elapsedTime + 's'">Generating...</span>
                            </span>
                            
                            <!-- Progress bar -->
                            <div wire:loading wire:target="generate" class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                                <div class="h-full bg-white/60 transition-all duration-1000 ease-linear" 
                                     :style="'width: ' + Math.min(100, (elapsedTime / estimatedTime) * 100) + '%'"></div>
                            </div>
                        </button>
                        
                        <!-- Estimated time remaining -->
                        <div wire:loading wire:target="generate" class="text-center">
                            <p class="text-xs text-gray-500" x-show="elapsedTime < estimatedTime" x-text="'Estimated time remaining: ~' + (estimatedTime - elapsedTime) + 's'"></p>
                            <p class="text-xs text-gray-500" x-show="elapsedTime >= estimatedTime">Almost there...</p>
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
                    @if($generatedHtml)
                        <div class="p-6 pb-3">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-700">Generated HTML</h3>
                                <span class="text-xs text-gray-500">{{ strlen($generatedHtml) }} characters</span>
                            </div>
                        </div>
                        <div class="flex-1 px-6 pb-6 overflow-hidden">
                            <textarea 
                                wire:model.live="generatedHtml"
                                class="w-full h-full bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm border-0 focus:ring-2 focus:ring-blue-500 resize-none"
                                style="font-family: 'Fira Code', 'Courier New', monospace; line-height: 1.6; tab-size: 2;"
                                spellcheck="false"
                            ></textarea>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                                <p class="text-sm">Enter a prompt and click Generate to see code here</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Panel - Preview -->
            <div class="w-1/2 bg-gray-100 flex flex-col">
                <div class="p-4 bg-white border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700">Live Preview</h3>
                </div>
                <div class="flex-1 overflow-auto p-4">
                    @if($generatedHtml)
                        <div class="bg-white rounded-lg shadow-sm h-full overflow-auto">
                            <iframe 
                                src="{{ $this->getPreviewUrl() }}"
                                class="w-full h-full border-0"
                                sandbox="allow-same-origin allow-scripts allow-forms"
                                title="HTML Preview"
                            ></iframe>
                        </div>
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
</div>

@script
<script>
    function copyToClipboard() {
        const textarea = document.querySelector('textarea[wire\\:model\\.live="generatedHtml"]');
        const code = textarea ? textarea.value : @js($generatedHtml);
        navigator.clipboard.writeText(code).then(() => {
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
            button.classList.add('bg-green-600');
            button.classList.remove('bg-blue-600');
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-600');
                button.classList.add('bg-blue-600');
            }, 2000);
        });
    }
</script>
@endscript
