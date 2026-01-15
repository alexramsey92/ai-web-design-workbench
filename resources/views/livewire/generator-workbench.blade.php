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
                            Copy Code
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Left Panel - Controls & Code -->
            <div class="w-1/2 flex flex-col border-r border-gray-200 bg-white">
                <!-- Input Form -->
                <div class="p-6 border-b border-gray-200 space-y-4">
                    <div>
                        <label for="prompt" class="block text-sm font-medium text-gray-700 mb-2">
                            Describe what you want to build
                        </label>
                        <textarea 
                            wire:model="prompt"
                            id="prompt"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="E.g., A landing page for a SaaS product with hero section, features, and pricing..."
                            @if($isGenerating) disabled @endif
                        ></textarea>
                        @error('prompt') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="styleLevel" class="block text-sm font-medium text-gray-700 mb-2">
                                Style Level
                            </label>
                            <select 
                                wire:model="styleLevel"
                                id="styleLevel"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                @if($isGenerating) disabled @endif
                            >
                                <option value="landing-page">Landing Page</option>
                                <option value="blog-post">Blog Post</option>
                                <option value="product-page">Product Page</option>
                            </select>
                        </div>
                    </div>

                    <button 
                        wire:click="generate"
                        @if($isGenerating) disabled @endif
                        class="w-full px-6 py-3 text-white bg-blue-600 rounded-lg font-medium hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition"
                    >
                        @if($isGenerating)
                            <span class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Generating...
                            </span>
                        @else
                            Generate HTML
                        @endif
                    </button>

                    @if($error)
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-800">{{ $error }}</p>
                        </div>
                    @endif
                </div>

                <!-- Generated Code -->
                <div class="flex-1 overflow-auto">
                    @if($generatedHtml)
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-700">Generated HTML</h3>
                                <span class="text-xs text-gray-500">{{ strlen($generatedHtml) }} characters</span>
                            </div>
                            <pre id="codeContent" class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-auto text-sm language-html"><code class="language-html">{{ $generatedHtml }}</code></pre>
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
                                id="previewFrame"
                                class="w-full h-full border-0"
                                sandbox="allow-same-origin"
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
    $wire.on('html-generated', () => {
        updatePreview();
    });

    function updatePreview() {
        const iframe = document.getElementById('previewFrame');
        const html = @js($generatedHtml);
        
        if (iframe && html) {
            const doc = iframe.contentDocument || iframe.contentWindow.document;
            doc.open();
            doc.write(html);
            doc.close();
        }

        // Highlight code
        const codeBlock = document.querySelector('#codeContent code');
        if (codeBlock && window.hljs) {
            window.hljs.highlightElement(codeBlock);
        }
    }

    function copyToClipboard() {
        const code = document.getElementById('codeContent').textContent;
        navigator.clipboard.writeText(code).then(() => {
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copied!';
            button.classList.add('bg-green-600');
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600');
            }, 2000);
        });
    }

    // Update preview when component updates
    Livewire.hook('morph.updated', () => {
        updatePreview();
    });
</script>
@endscript

