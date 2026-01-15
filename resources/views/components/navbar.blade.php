<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">AI Web Design Workbench</a>
            </div>

            <!-- Desktop links -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Home</a>
                <a href="{{ route('workbench') }}" class="text-sm font-medium {{ request()->routeIs('workbench') ? 'px-4 py-2 bg-blue-600 text-white rounded-lg' : 'px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700' }}">Launch Workbench</a>
                <a href="https://github.com/alexramsey92/ai-web-design-workbench" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-gray-900 inline-flex items-center gap-2" title="View on GitHub">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.387.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.416-4.042-1.416-.546-1.387-1.333-1.757-1.333-1.757-1.089-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.418-1.305.762-1.605-2.665-.305-5.466-1.332-5.466-5.931 0-1.31.468-2.381 1.235-3.221-.123-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.3 1.23.957-.266 1.98-.399 3-.405 1.02.006 2.043.139 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.655 1.653.243 2.874.12 3.176.77.84 1.23 1.911 1.23 3.221 0 4.61-2.807 5.624-5.48 5.921.43.369.823 1.096.823 2.214 0 1.598-.015 2.887-.015 3.281 0 .319.216.694.825.576C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                    <span class="sr-only">GitHub</span>
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" aria-expanded="false" aria-controls="mobile-menu" class="p-2 rounded-md text-gray-600 hover:text-gray-900 focus:outline-none">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden hidden px-4 pb-4">
        <a href="{{ route('home') }}" class="block py-2 text-sm {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-700' }}">Home</a>
        <a href="{{ route('workbench') }}" class="block py-2 text-sm text-gray-700">Launch Workbench</a>
        <a href="https://github.com/alexramsey92/ai-web-design-workbench" target="_blank" rel="noopener noreferrer" class="block py-2 text-sm text-gray-700">GitHub</a>
    </div>

    <script>
        (function(){
            const btn = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            if (btn && menu) {
                btn.addEventListener('click', () => {
                    const isHidden = menu.classList.toggle('hidden');
                    btn.setAttribute('aria-expanded', !isHidden);
                });
            }
        })();
    </script>
</nav>