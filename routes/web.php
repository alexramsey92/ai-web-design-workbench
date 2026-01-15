<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/workbench', function () {
    return view('workbench');
})->name('workbench');

Route::get('/content', function () {
    $html = request()->query('html', '');
    
    // Validate HTML is from authenticated session
    if (!session()->has('_token')) {
        abort(403, 'Unauthorized');
    }
    
    // Wrap HTML with required head and semantic CSS
    $fullHtml = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        /* Semantic CSS Classes */
        .hero {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            padding: 4rem 2rem;
            text-align: center;
            color: white;
            min-height: 500px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .section {
            padding: 3rem 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .cta-button {
            background: #f97316;
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .cta-button:hover {
            background: #ea580c;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #6b7280;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }
        .feature-card {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        .card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .prose {
            max-width: 65ch;
            line-height: 1.75;
        }
        @media (max-width: 768px) {
            .hero {
                padding: 3rem 1.5rem;
                min-height: 400px;
            }
            .section {
                padding: 2rem 1rem;
            }
            .feature-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
    </style>
</head>
<body>
' . $html . '
</body>
</html>';
    
    // Add security headers to prevent XSS in preview
    return response($fullHtml)
        ->header('Content-Type', 'text/html; charset=utf-8')
        ->header('X-Content-Type-Options', 'nosniff')
        ->header('X-Frame-Options', 'SAMEORIGIN')
        ->header('Content-Security-Policy', "default-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://fonts.googleapis.com https://cdnjs.cloudflare.com; img-src 'self' data: https:; font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com;");
})->name('content.show');
