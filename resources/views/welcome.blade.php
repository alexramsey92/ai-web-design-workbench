<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Web Design Workbench</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-hero {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <section class="gradient-hero py-20 lg:py-32 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    AI Web Design Workbench
                </h1>
                <p class="text-xl md:text-2xl opacity-90 mb-8 leading-relaxed">
                    Generate production-ready HTML in minutes with AI-powered templates, curated Tailwind classes, and semantic CSS.
                </p>
                <div class="flex justify-center gap-4 flex-wrap">
                    <a href="https://github.com/alexramsey92/ai-web-design-workbench" target="_blank" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-lg bg-white text-blue-600 shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-200">
                        View on GitHub
                    </a>
                    <a href="#getting-started" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold rounded-lg bg-transparent text-white border-2 border-white hover:bg-white hover:text-blue-600 transition-all duration-200">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="getting-started" class="py-16 lg:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">Getting Started</h2>
                
                <div class="bg-gray-100 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">🚀 Quick Start</h3>
                    <div class="space-y-3 font-mono text-sm">
                        <div class="bg-gray-900 text-green-400 p-4 rounded">
                            <p class="mb-2"># Generate a landing page</p>
                            <p>php artisan html:generate landing-page \<br>
                            &nbsp;&nbsp;--company="Your Company" \<br>
                            &nbsp;&nbsp;--headline="Build Something Amazing" \<br>
                            &nbsp;&nbsp;--style=full \<br>
                            &nbsp;&nbsp;--output=output/landing.html</p>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
                        <div class="text-4xl mb-4">🎨</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Three Style Levels</h3>
                        <p class="text-gray-600">Choose full, mid, or low styling density for your generated HTML</p>
                    </div>
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
                        <div class="text-4xl mb-4">🎯</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Semantic Classes</h3>
                        <p class="text-gray-600">Clean, maintainable HTML with semantic CSS classes</p>
                    </div>
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
                        <div class="text-4xl mb-4">🤖</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Optional AI</h3>
                        <p class="text-gray-600">Works perfectly with or without Anthropic Claude API</p>
                    </div>
                </div>

                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Available Commands</h3>
                    <div class="space-y-2 font-mono text-sm text-gray-700">
                        <p><span class="text-blue-600">php artisan html:generate</span> - Generate HTML content</p>
                        <p><span class="text-blue-600">php artisan html:style-levels</span> - View available styling levels</p>
                        <p><span class="text-blue-600">php artisan ai:status</span> - Check AI configuration</p>
                    </div>
                </div>

                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Ready to Build?</h3>
                    <p class="text-lg text-gray-600 mb-6">Check out the full documentation to start generating beautiful HTML</p>
                    <a href="https://github.com/alexramsey92/ai-web-design-workbench#readme" target="_blank" class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 shadow transition-all duration-200">
                        Read the Docs
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-12 bg-gray-900 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} AI Web Design Workbench. 
                    <a href="https://github.com/alexramsey92/ai-web-design-workbench" class="text-blue-400 hover:text-blue-300">Open Source</a> 
                    under MIT License
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
