<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MCP (Model Context Protocol) Configuration
    |--------------------------------------------------------------------------
    |
    | Configure MCP server connection for AI-powered HTML generation.
    | Set MCP_ENABLED=true in .env to enable MCP features.
    |
    */

    'enabled' => env('MCP_ENABLED', false),
    
    'server_url' => env('MCP_SERVER_URL', 'http://localhost:3000'),
    
    'api_key' => env('MCP_API_KEY', ''),
    
    'timeout' => env('MCP_TIMEOUT', 30),
    
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
