<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Livewire NProgress shim: ensure import_nprogress.default.configure exists for Livewire -->
    <script>
        (function() {
            // Create a safe shim that delegates to window.NProgress when available
            function safeN() {
                const base = window.NProgress || {};
                return {
                    configure: base.configure ? base.configure.bind(base) : function() {},
                    start: base.start ? base.start.bind(base) : function() {},
                    done: base.done ? base.done.bind(base) : function() {},
                    set: base.set ? base.set.bind(base) : function() {},
                    inc: base.inc ? base.inc.bind(base) : function() {},
                };
            }

            if (!window.import_nprogress) {
                window.import_nprogress = { default: safeN() };
            } else if (!window.import_nprogress.default || !window.import_nprogress.default.configure) {
                window.import_nprogress.default = safeN();
            }
        })();
    </script>
    
    <style>
        @keyframes rainbow {
            0% { color: #ef4444; }
            14% { color: #f97316; }
            28% { color: #eab308; }
            42% { color: #22c55e; }
            57% { color: #3b82f6; }
            71% { color: #8b5cf6; }
            85% { color: #ec4899; }
            100% { color: #ef4444; }
        }
        .rainbow-text {
            animation: rainbow 2s linear infinite;
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    @include('components.navbar')

    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
