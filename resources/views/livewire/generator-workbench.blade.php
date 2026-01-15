<div class="fixed inset-0 bg-gray-50 overflow-hidden">
    <div class="h-full flex flex-col">
        <!-- Draft Banner -->
        <div id="draft-banner" 
             style="display: none;"
             class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200 px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-900">
                            <span class="font-semibold">Draft Saved</span> — Your work is automatically saved
                        </p>
                    </div>
                </div>
                <button 
                    onclick="clearDraft()"
                    class="text-xs text-green-700 hover:text-green-900 font-medium px-3 py-1.5 rounded-lg hover:bg-green-100 transition">
                    <i class="fas fa-times mr-1.5"></i>Dismiss
                </button>
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
                        <textarea 
                            wire:model="prompt"
                            id="prompt"
                            rows="3"
                            class="w-full px-3 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
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
                                    onclick="copyToClipboard()" 
                                    class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                                    <i class="fas fa-copy mr-1.5"></i>Copy
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 px-6 pb-6 overflow-hidden">
                        <div id="monaco-editor" class="w-full h-full rounded-lg overflow-hidden border-0"></div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Preview -->
            <div class="w-1/2 bg-gray-100 flex flex-col">
                <div class="p-4 bg-white border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700">Live Preview</h3>
                </div>
                <div class="flex-1 overflow-auto p-4">
                    <div class="bg-white rounded-lg shadow-sm h-full overflow-auto">
                        @if($generatedHtml)
                            <iframe 
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
    </div>
</div>

<script>
    let editor = null;
    let isUpdatingFromWire = false;
    let isUpdatingFromEditor = false;

    // Placeholder manager (Livewire-aware)
    const placeholders = @js($examplePrompts);
    const placeholderManager = (function() {
        let idx = 0;
        let timer = null;
        const el = () => document.getElementById('prompt');

        function tick() {
            const input = el();
            if (!input) return;
            if (input.value.trim() === '') {
                input.placeholder = placeholders[idx % placeholders.length];
                idx++;
            }
        }

        function start() {
            stop();
            tick();
            timer = setInterval(tick, 3000);
        }

        function stop() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        function restartIfEmpty() {
            const input = el();
            if (!input) return;
            if (input.value.trim() === '') start();
            else stop();
        }

        return { start, stop, restartIfEmpty };
    })();

    // Wire up input listener and Livewire hooks
    function attachPromptListeners() {
        const promptInput = document.getElementById('prompt');
        if (!promptInput) return;

        // Stop cycling while user types
        promptInput.removeEventListener('__placeholder_input', promptInput.__placeholder_handler);
        promptInput.__placeholder_handler = function() {
            if (this.value.trim() === '') {
                placeholderManager.start();
            } else {
                placeholderManager.stop();
            }
        };
        promptInput.addEventListener('input', promptInput.__placeholder_handler, { passive: true });

        // Ensure correct state after Livewire updates
        if (window.Livewire && Livewire.hook) {
            if (!window.__prompt_livewire_hook_registered) {
                window.__prompt_livewire_hook_registered = true;

                // After messages are processed, re-attach handlers in case Livewire replaced the element
                Livewire.hook('message.processed', () => {
                    setTimeout(() => {
                        attachPromptListeners();
                        placeholderManager.restartIfEmpty();
                    }, 0);
                });

                // Prevent accidental toJSON method calls from reaching the server
                Livewire.hook('message.sending', (message) => {
                    try {
                        if (message && message.components) {
                            message.components.forEach((c) => {
                                if (c.calls && Array.isArray(c.calls)) {
                                    c.calls = c.calls.filter(call => call.method !== 'toJSON');
                                }
                            });
                        }
                    } catch (e) {
                        console.warn('Failed to sanitize Livewire message', e);
                    }
                });
            }
        }

        // Start cycling on attach
        placeholderManager.restartIfEmpty();
    }

    // Attach on load and after Livewire navigation
    document.addEventListener('DOMContentLoaded', attachPromptListeners);
    document.addEventListener('livewire:load', attachPromptListeners);
    document.addEventListener('livewire:navigated', attachPromptListeners);

    // Draft management
    function checkDraft() {
        const draft = localStorage.getItem('html-draft');
        const banner = document.getElementById('draft-banner');
        if (draft && draft.trim().length > 0 && banner) {
            banner.style.display = 'block';
            Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('loadDraft', draft);
        }
    }

    function clearDraft() {
        localStorage.removeItem('html-draft');
        const banner = document.getElementById('draft-banner');
        if (banner) banner.style.display = 'none';
        Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id')).call('clear');
    }

    function updateDraft(value) {
        if (value && value.trim().length > 0) {
            localStorage.setItem('html-draft', value);
            const banner = document.getElementById('draft-banner');
            if (banner) banner.style.display = 'block';
        }
    }

    // Monaco Editor
    function initMonaco() {
            // Load Monaco loader dynamically if needed to avoid AMD 'anonymous define' conflicts
        function loadMonacoLoader(cb) {
            if (window.require && window.require.config) {
                return cb();
            }

            if (document.getElementById('monaco-loader')) {
                // already loading; poll until ready
                const poll = setInterval(() => {
                    if (window.require && window.require.config) {
                        clearInterval(poll);
                        cb();
                    }
                }, 50);
                return;
            }

            const s = document.createElement('script');
            s.id = 'monaco-loader';
            s.src = 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js';
            s.crossOrigin = 'anonymous';
            s.onload = () => cb();
            s.onerror = () => console.warn('Failed to load Monaco loader');
            document.head.appendChild(s);
        }

        loadMonacoLoader(() => {
            require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' } });
            require(['vs/editor/editor.main'], function() {
                editor = monaco.editor.create(document.getElementById('monaco-editor'), {
                    value: @js($generatedHtml),
                    language: 'html',
                    theme: 'vs-dark',
                    automaticLayout: true,
                    fontSize: 14,
                    lineNumbers: 'on',
                    minimap: { enabled: true },
                    scrollBeyondLastLine: false,
                    wordWrap: 'on',
                    tabSize: 2,
                    formatOnPaste: true,
                    formatOnType: true,
                });

            // Listen for content changes in Monaco
            editor.onDidChangeModelContent(() => {
                if (!isUpdatingFromWire) {
                    isUpdatingFromEditor = true;
                    const value = editor.getValue();
                    if (componentId) {
                        Livewire.find(componentId).set('generatedHtml', value);
                    }
                    updateDraft(value);
                    setTimeout(() => { isUpdatingFromEditor = false; }, 50);
                }
            });

            // Listen for Livewire updates
            Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                succeed(({ snapshot, effect }) => {
                    if (!isUpdatingFromEditor && component.id === componentId) {
                        const newValue = component.canonical.data.generatedHtml || '';
                        if (editor && editor.getValue() !== newValue) {
                            isUpdatingFromWire = true;
                            editor.setValue(newValue);
                            setTimeout(() => { isUpdatingFromWire = false; }, 50);
                        }
                    }
                });
            });
        });
    }

    function copyToClipboard() {
        const code = editor ? editor.getValue() : @js($generatedHtml);
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

    // Initialize everything when page loads
    function initializeWorkbench() {
        // Monaco, draft, and prompt handlers
        if (typeof initMonaco === 'function') initMonaco();
        if (typeof checkDraft === 'function') checkDraft();
        attachPromptListeners();
    }

    document.addEventListener('livewire:navigated', () => {
        initializeWorkbench();
    });

    // Run on initial load
    if (document.readyState !== 'loading') {
        setTimeout(initializeWorkbench, 100);
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initializeWorkbench, 100);
        });
    }
</script>

