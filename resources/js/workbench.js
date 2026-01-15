export default (function () {
    const root = document.querySelector('[data-workbench]');
    if (!root) return;

    const placeholders = JSON.parse(root.getAttribute('data-placeholders') || '[]');
    const initialGenerated = root.getAttribute('data-generated') || '';
    const componentId = root.getAttribute('data-wire-id') || (document.querySelector('[wire\\:id]') ? document.querySelector('[wire\\:id]').getAttribute('wire:id') : null);
    console.log('workbench init', { componentId, initialGeneratedLength: (initialGenerated || '').length });
    let editor = null;
    let isUpdatingFromWire = false;
    let isUpdatingFromEditor = false;
    let isInitializingMonaco = false;
    let monacoInitPromise = null;

    // Helper: returns true if the editor's DOM node is still attached to document
    function isEditorMounted() {
        try {
            return editor && typeof editor.getDomNode === 'function' && document.contains(editor.getDomNode());
        } catch (e) {
            return false;
        }
    }

    // Placeholder manager
    const placeholderManager = (function () {
        let idx = 0;
        let timer = null;
        const el = () => document.getElementById('prompt');

        function tick() {
            const input = el();
            if (!input) return;
            if (input.value.trim() === '') {
                const newPh = placeholders[idx % placeholders.length];
                input.placeholder = newPh;
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

    function attachPromptListeners() {
        const promptInput = document.getElementById('prompt');
        if (!promptInput) return;

        // remove previous
        if (promptInput.__placeholder_handler) {
            promptInput.removeEventListener('input', promptInput.__placeholder_handler);
        }

        promptInput.__placeholder_handler = function () {
            if (this.value.trim() === '') {
                placeholderManager.start();
            } else {
                placeholderManager.stop();
            }
        };
        promptInput.addEventListener('input', promptInput.__placeholder_handler, { passive: true });

        // Livewire hooks
        if (window.Livewire && Livewire.hook && !window.__prompt_livewire_hook_registered) {
            window.__prompt_livewire_hook_registered = true;
            Livewire.hook('message.processed', () => {
                setTimeout(() => {
                    attachPromptListeners();
                    placeholderManager.restartIfEmpty();
                }, 0);
            });

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

        placeholderManager.restartIfEmpty();
    }

    function checkDraft() {
        const draft = localStorage.getItem('html-draft');
        const overlay = document.getElementById('draft-overlay');
        const dismiss = document.getElementById('draft-overlay-dismiss');
        if (draft && draft.trim().length > 0 && overlay) {
            overlay.style.display = 'inline-flex';
            if (dismiss) dismiss.style.display = 'inline-block';
            overlay.setAttribute('data-has-draft', '1');
            window.__workbench_saved_draft = draft;
        } else if (overlay) {
            overlay.style.display = 'none';
            if (dismiss) dismiss.style.display = 'none';
            overlay.removeAttribute('data-has-draft');
            window.__workbench_saved_draft = null;
        }
    }

    function clearDraft() {
        localStorage.removeItem('html-draft');
        const overlay = document.getElementById('draft-overlay');
        const dismiss = document.getElementById('draft-overlay-dismiss');
        if (overlay) overlay.style.display = 'none';
        if (dismiss) dismiss.style.display = 'none';
        window.__workbench_saved_draft = null;
        if (componentId) {
            const comp = Livewire.find(componentId);
            if (comp) {
                try { comp.call('clearDraft'); } catch (e) { }
                try { comp.call('clear'); } catch (e) { }
            }
        }
    }

    function restoreDraft() {
        const draft = window.__workbench_saved_draft || localStorage.getItem('html-draft');
        if (!draft) return;
        const overlay = document.getElementById('draft-overlay');
        const dismiss = document.getElementById('draft-overlay-dismiss');
        if (overlay) overlay.style.display = 'none';
        if (dismiss) dismiss.style.display = 'none';
        window.__workbench_saved_draft = null;
        if (componentId) {
            const comp = Livewire.find(componentId);
            if (comp) comp.call('loadDraft', draft);
        }
    }

    // expose clearDraft to global for the button
    window.clearDraft = clearDraft;
    // expose restoreDraft for the view
    window.restoreDraft = restoreDraft;

    // Listen for server-dispatched event when HTML is generated
    document.addEventListener('html-generated', (e) => {
        try {
            const payload = e && (e.detail || e);
            const html = (payload && payload.html) ? payload.html : (typeof payload === 'string' ? payload : '');
            console.log('workbench: received html-generated event, length=', (html || '').length);
            if (editor && editor.getValue && editor.setValue) {
                isUpdatingFromWire = true;
                editor.setValue(html);
                setTimeout(() => { isUpdatingFromWire = false; }, 50);
            } else {
                // editor not initialized yet; initialize with the new content
                try { initMonaco(html); } catch (e) { console.warn('Failed to initMonaco from html-generated', e); }
            }
            updateDraft(html);
        } catch (e) {
            console.warn('Failed handling html-generated', e);
        }
    });

    function updateDraft(value) {
        if (value && value.trim().length > 0) {
            localStorage.setItem('html-draft', value);
            const banner = document.getElementById('draft-banner');
            if (banner) banner.style.display = 'block';
        }
    }

    // Monaco
    function loadMonacoLoader(cb) {
        if (window.require && window.require.config) return cb();
        if (document.getElementById('monaco-loader')) {
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
        s.onerror = () => cb();
        document.head.appendChild(s);
    }

    function createMonacoFallback(initial) {
        const container = document.getElementById('monaco-editor');
        container.innerHTML = '';
        const ta = document.createElement('textarea');
        ta.className = 'w-full h-full p-4 font-mono text-sm bg-gray-900 text-gray-200 rounded-lg border-0 resize-none';
        // Ensure fallback textarea has a sensible min-height so it is usable
        ta.style.minHeight = '240px';
        ta.style.boxSizing = 'border-box';
        ta.spellcheck = false;
        ta.value = initial || '';
        ta.addEventListener('input', () => {
            const v = ta.value;
            if (componentId) {
                const comp = Livewire.find(componentId);
                if (comp) comp.set('generatedHtml', v);
            }
            updateDraft(v);
        });

        if (window.Livewire && Livewire.hook) {
            Livewire.hook('message.processed', () => {
                if (componentId) {
                    const comp = Livewire.find(componentId);
                    if (comp && comp.get) {
                        const current = comp.get('generatedHtml') || '';
                        if (ta.value !== current) ta.value = current;
                    }
                }
            });
        }

        container.appendChild(ta);
    }

    function copyToClipboard() {
        const code = editor ? (typeof editor.getValue === 'function' ? editor.getValue() : '') : (document.querySelector('#monaco-editor textarea') ? document.querySelector('#monaco-editor textarea').value : '');
        navigator.clipboard.writeText(code).then(() => {
            const button = (document.activeElement && document.activeElement.tagName === 'BUTTON') ? document.activeElement : null;
            if (button) {
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-blue-600');
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-blue-600');
                }, 2000);
            }
        });
    }

    // expose copy to global for the view
    window.copyToClipboard = copyToClipboard;

    function initMonaco(initial) {
        const container = document.getElementById('monaco-editor');
        console.log('initMonaco called', { containerExists: !!container, editorExists: !!editor, monacoInitPromiseExists: !!monacoInitPromise });
        if (!container) return;

        // If init already in progress, return that promise to avoid races
        if (monacoInitPromise) {
            console.log('initMonaco: init already in progress, returning existing promise');
            return monacoInitPromise;
        }

        // If editor is already created, skip
        if (editor) {
            console.log('initMonaco: editor already initialized, skipping');
            return Promise.resolve();
        }

        // Clear any stray DOM inside the container to avoid Monaco context attribute collisions
        container.innerHTML = '';

        monacoInitPromise = new Promise((resolve) => {
            loadMonacoLoader(() => {
                try {
                    require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' } });
                    require(['vs/editor/editor.main'], function () {
                        try {
                            // Defensive cleanup: remove any Monaco/Context attributes that may remain
                            ['context', 'data-context', 'data-monaco-context'].forEach(a => { try { if (container.hasAttribute && container.hasAttribute(a)) container.removeAttribute(a); } catch (ee) {} });

                            // Prevent concurrent inits
                            if (isInitializingMonaco) {
                                console.log('Monaco initialization already in progress inside loader, resolving');
                                resolve();
                                return;
                            }
                            isInitializingMonaco = true;

                            // Defensive: remove any attributes with suspicious Tailwind class lists that may have been set as attributes by mistake
                            try {
                                const suspicious = /w-full|h-full|rounded-lg|overflow-hidden|border-0/;
                                container.getAttributeNames().forEach(name => {
                                    try {
                                        const v = container.getAttribute(name);
                                        if (v && typeof v === 'string' && suspicious.test(v)) {
                                            console.warn('Removing suspicious attribute from container:', name, v);
                                            container.removeAttribute(name);
                                        }
                                    } catch (e) { /* ignore */ }
                                });
                            } catch (e) { /* ignore */ }

                            // Remove any stray 'context' attributes inside the container (Monaco owns these)
                            try {
                                const removed = [];
                                container.querySelectorAll('[context]').forEach(el => {
                                    removed.push({ node: el.tagName, value: el.getAttribute('context') });
                                    el.removeAttribute('context');
                                });
                                if (removed.length) console.warn('Removed existing context attributes from container children:', removed);
                            } catch (e) { /* ignore */ }

                            // Use an inner host element so attributes on the container cannot interfere with Monaco
                            const host = document.createElement('div');
                            host.className = 'w-full h-full';
                            // Ensure the host has explicit sizing so Monaco can measure correctly
                            host.style.width = '100%';
                            host.style.height = '100%';
                            host.style.minHeight = '400px';
                            host.style.display = 'block';
                            host.style.boxSizing = 'border-box';

                            container.innerHTML = '';
                            container.appendChild(host);

                            console.log('Creating Monaco editor on host element...');
                            editor = monaco.editor.create(host, {
                                value: initial || '',
                                language: 'html',
                                theme: 'vs-dark',
                                automaticLayout: false, // we'll trigger layout manually
                                fontSize: 14,
                                lineNumbers: 'on',
                                minimap: { enabled: true },
                                scrollBeyondLastLine: false,
                                wordWrap: 'on',
                                tabSize: 2,
                                formatOnPaste: true,
                                formatOnType: true,
                            });

                            // Force an initial layout and respond to future resizes
                            try {
                                editor.layout();
                                setTimeout(() => { try { editor.layout(); } catch (e) {} }, 50);
                                // Keep layout in sync with container using ResizeObserver
                                if (typeof ResizeObserver !== 'undefined') {
                                    const ro = new ResizeObserver(() => { try { editor.layout(); } catch (e) {} });
                                    ro.observe(host);
                                    // store observer to dispose later if needed
                                    editor.__resizeObserver = ro;
                                }
                            } catch (e) {
                                console.warn('Failed to run Monaco layout', e);
                            }
                        } catch (e) {
                            console.warn('Monaco create failed, falling back:', e);
                            try {
                                console.error('Monaco create error: container snapshot:', container && container.outerHTML ? container.outerHTML.slice(0,500) : 'no container');
                            } catch (ee) { console.warn('Failed to snapshot container', ee); }
                            createMonacoFallback(initial);
                            isInitializingMonaco = false;
                            resolve();
                            return;
                        } finally {
                            isInitializingMonaco = false;
                        }

                        try {
                            // Remove any leftover context attributes that might have been applied to the inner host
                            try { host.querySelectorAll('[context]').forEach(el => el.removeAttribute('context')); } catch (e) { }

                            editor.onDidChangeModelContent(() => {
                                if (!isUpdatingFromWire) {
                                    isUpdatingFromEditor = true;
                                    const value = editor.getValue();
                                    if (componentId) {
                                        const comp = Livewire.find(componentId);
                                        if (comp) comp.set('generatedHtml', value);
                                    }
                                    updateDraft(value);
                                    setTimeout(() => { isUpdatingFromEditor = false; }, 50);
                                }
                            });

                            Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                                try {
                                    if (!isUpdatingFromEditor && component && component.id === componentId) {
                                        const newValue = (component.canonical && component.canonical.data && component.canonical.data.generatedHtml) ? component.canonical.data.generatedHtml : '';
                                        if (editor && editor.getValue && editor.getValue() !== newValue) {
                                            isUpdatingFromWire = true;
                                            editor.setValue(newValue);
                                            setTimeout(() => { isUpdatingFromWire = false; }, 50);
                                        }
                                    }
                                } catch (e) {
                                    console.warn('Livewire commit handler failed to sync editor value', e);
                                }
                            });
                        } catch (ee) { console.warn('Failed to attach Monaco handlers', ee); }

                        isInitializingMonaco = false;
                        resolve();
                    });
                } catch (e) {
                    try { createMonacoFallback(initial); } catch (ee) { console.error('Fallback also failed', ee); }
                    isInitializingMonaco = false;
                    resolve();
                }
            });
        }).finally(() => { monacoInitPromise = null; });

        return monacoInitPromise;
    }

    function initializeWorkbench() {
        attachPromptListeners();
        checkDraft();
        initMonaco(initialGenerated);

        // Ensure Monaco survives Livewire re-renders: if Livewire updates the DOM, re-init if needed
        if (window.Livewire && Livewire.hook && !window.__workbench_livewire_processed_registered) {
            window.__workbench_livewire_processed_registered = true;
            Livewire.hook('message.processed', () => {
                setTimeout(() => {
                    // Reattach prompt listeners and placeholder state
                    attachPromptListeners();
                    placeholderManager.restartIfEmpty();

                    // Gather runtime state for diagnostics
                    const container = document.getElementById('monaco-editor');
                    const containerExists = !!container;
                    const mounted = isEditorMounted();
                    console.log('workbench: Livewire message.processed — containerExists=', containerExists, 'editorMounted=', mounted, 'editor=', !!editor);

                    // Determine latest generatedHtml from Livewire component if available
                    let latest = '';
                    try {
                        if (componentId) {
                            const comp = Livewire.find(componentId);
                            if (comp && comp.get) latest = comp.get('generatedHtml') || '';
                        }
                    } catch (e) { console.warn('Failed to read latest generatedHtml from component', e); }

                    // If the editor's DOM was removed, dispose and re-init with the latest content after a short delay
                    if (!mounted) {
                        try { if (editor && typeof editor.dispose === 'function') editor.dispose(); } catch (e) { }
                        editor = null;

                        setTimeout(() => {
                            console.log('workbench: re-initializing Monaco after Livewire update, latest length=', (latest || '').length);
                            initMonaco(latest);
                        }, 50);

                        return;
                    }

                    // If editor still attached, ensure it has the latest value and trigger layout
                    try {
                        if (editor && editor.layout) {
                            try { editor.layout(); } catch (e) { /* ignore */ }
                        }

                        if (componentId) {
                            const comp = Livewire.find(componentId);
                            const newVal = comp && comp.get ? comp.get('generatedHtml') || '' : '';
                            if (editor && editor.getValue && editor.getValue() !== newVal) {
                                isUpdatingFromWire = true;
                                editor.setValue(newVal);
                                setTimeout(() => { isUpdatingFromWire = false; }, 50);
                            }
                        }
                    } catch (e) { console.warn('Error syncing existing editor after Livewire update', e); }
                }, 50);
            });
        }
    }

    document.addEventListener('livewire:navigated', initializeWorkbench);
    if (document.readyState !== 'loading') initializeWorkbench();
    else document.addEventListener('DOMContentLoaded', initializeWorkbench);

    // expose clearDraft to global for the button
    window.clearDraft = clearDraft;

})();