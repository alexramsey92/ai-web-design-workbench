<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Content Generation Configuration
    |--------------------------------------------------------------------------
    |
    | Configure AI provider for AI-powered HTML generation.
    | Set AI_CONTENT_GENERATION_ENABLED=true in .env to enable AI features.
    |
    */

    'enabled' => env('AI_CONTENT_GENERATION_ENABLED', false),
    
    'provider' => env('AI_PROVIDER', 'anthropic'),
    
    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY', ''),
        'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-20250514'),
        'max_tokens' => (int) env('ANTHROPIC_MAX_TOKENS', 4096),
        'temperature' => (float) env('ANTHROPIC_TEMPERATURE', 0.7),
    ],
    
    'timeout' => env('AI_TIMEOUT', 60),
    
    'rate_limiting' => [
        'enabled' => env('AI_RATE_LIMIT_ENABLED', true),
        'max_requests_per_hour' => env('AI_MAX_REQUESTS_PER_HOUR', 60),
        'max_requests_per_day' => env('AI_MAX_REQUESTS_PER_DAY', 200),
    ],
    
    'logging_enabled' => env('AI_LOGGING_ENABLED', true),
    
    /*
    |--------------------------------------------------------------------------
    | MCP Generation Settings
    |--------------------------------------------------------------------------
    */
    
    'max_retries' => 3,
    
    'retry_delay' => 1000, // milliseconds    
    'use_semantic_classes' => true, // Use semantic CSS classes vs pure Tailwind utilities    
    /*
    |--------------------------------------------------------------------------
    | Prompt Templates
    |--------------------------------------------------------------------------
    |
    | System prompts and templates for different generation types
    |
    */
    
    'prompts' => [
        'system' => 'You are an expert web designer specializing in modern, accessible HTML with Tailwind CSS. Generate clean, semantic HTML that follows best practices. Only use Tailwind classes from the provided whitelist.',
        
        'landing_page' => [
            'hero' => 'Generate a hero section for a {industry} company. Include a headline, subheadline, CTA button, and optional image placeholder. Style level: {style_level}.',
            'features' => 'Generate a features section with {feature_count} features for a {industry} product. Include icons, titles, and descriptions. Style level: {style_level}.',
            'cta' => 'Generate a call-to-action section for {action_type}. Include headline, description, and prominent CTA button. Style level: {style_level}.',
            'footer' => 'Generate a footer with {company_name}, navigation links, and social media icons. Style level: {style_level}.',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Guardrails
    |--------------------------------------------------------------------------
    |
    | Validation rules and constraints for generated content
    |
    */
    
    'guardrails' => [
        'max_nesting_depth' => 10,
        'max_element_count' => 500,
        'allowed_tags' => [
            'div', 'section', 'article', 'header', 'footer', 'nav', 'main', 'aside',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'p', 'span', 'a', 'button',
            'ul', 'ol', 'li',
            'img', 'svg', 'path',
            'form', 'input', 'textarea', 'label', 'select', 'option',
        ],
        'require_semantic_html' => true,
        'require_accessible_markup' => true,
    ],
];
