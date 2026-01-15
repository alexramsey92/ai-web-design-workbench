<x-app-layout>
    <div class="gradient-hero py-20 lg:py-32 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    AI Web Design Workbench
                </h1>
                <p class="text-xl md:text-2xl opacity-90 mb-8 leading-relaxed">
                    Generate production-ready HTML in real-time with AI-powered templates, curated Tailwind classes, and semantic CSS.
                </p>
                <div class="flex justify-center gap-4 flex-wrap">
                    <a href="{{ route('workbench') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-lg bg-white text-blue-600 shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-200">
                        Launch Workbench
                    </a>
                    <a href="https://github.com/alexramsey92/ai-web-design-workbench" target="_blank" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-lg bg-transparent text-white border-2 border-white hover:bg-white hover:text-blue-600 transition-all duration-200">
                        View on GitHub
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="py-16 lg:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">How It Works</h2>
                
                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
                        <div class="text-4xl mb-4">✍️</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">1. Describe Your Page</h3>
                        <p class="text-gray-600">Enter a description of what you want to build</p>
                    </div>
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
                        <div class="text-4xl mb-4">🎨</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">2. Choose Style Level</h3>
                        <p class="text-gray-600">Select full, mid, or low styling density</p>
                    </div>
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
                        <div class="text-4xl mb-4">⚡</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">3. Preview & Copy</h3>
                        <p class="text-gray-600">See live preview and copy the code</p>
                    </div>
                </div>

                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Ready to Build?</h3>
                    <p class="text-lg text-gray-600 mb-6">Launch the workbench and start generating beautiful HTML in seconds</p>
                    <a href="{{ route('workbench') }}" class="inline-flex items-center justify-center px-8 py-3 text-base font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow transition-all duration-200">
                        Get Started →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
        .gradient-hero {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
    </style>
</x-app-layout>
