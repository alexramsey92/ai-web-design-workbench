<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Exceptions\MCPException;

class AnthropicClient
{
    protected string $serverUrl;
    protected ?string $apiKey;
    protected int $timeout;
    protected int $maxRetries;
    protected int $retryDelay;

    public function __construct()
    {
        $this->serverUrl = config('mcp.anthropic.api_url', 'https://api.anthropic.com/v1');
        $this->apiKey = config('mcp.anthropic.api_key');
        $this->timeout = config('mcp.timeout');
        $this->maxRetries = config('mcp.max_retries');
        $this->retryDelay = config('mcp.retry_delay');
    }

    /**
     * Check if MCP is enabled and configured
     */
    public function isEnabled(): bool
    {
        return config('mcp.enabled', false) && !empty($this->serverUrl);
    }

    /**
     * Generate HTML content via MCP server
     */
    public function generate(string $prompt, array $context = []): string
    {
        if (!$this->isEnabled()) {
            throw new MCPException('MCP is not enabled or configured. Check your .env settings.');
        }

        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->maxRetries) {
            try {
                $whitelistedClasses = $this->getWhitelistedClasses($context['style_level'] ?? 'full');
                
                $systemPrompt = "You are an expert HTML/Tailwind CSS developer. Generate clean, production-ready HTML using ONLY these Tailwind classes: " . implode(', ', $whitelistedClasses);
                
                if (!empty($context['use_semantic'])) {
                    $systemPrompt .= "\n\nYou can also use these semantic CSS classes: .hero, .gradient-hero, .btn-primary, .btn-secondary, .feature-card, .prose, .card, .container-custom";
                }
                
                $userPrompt = $prompt . "\n\nGenerate complete, valid HTML with proper structure. Include <!DOCTYPE html>, <html>, <head> with meta tags and title, and <body>. Use Tailwind CDN: <script src=\"https://cdn.tailwindcss.com\"></script>";

                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'x-api-key' => $this->apiKey,
                        'anthropic-version' => '2023-06-01',
                        'Content-Type' => 'application/json',
                    ])
                    ->post('https://api.anthropic.com/v1/messages', [
                        'model' => config('mcp.anthropic.model'),
                        'max_tokens' => (int) config('mcp.anthropic.max_tokens', 4096),
                        'temperature' => (float) config('mcp.anthropic.temperature', 0.7),
                        'system' => $systemPrompt,
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => $userPrompt,
                            ]
                        ],
                    ]);

                if ($response->successful()) {
                    return $this->parseAnthropicResponse($response->json());
                }

                throw new MCPException(
                    "Anthropic API responded with status {$response->status()}: {$response->body()}"
                );
            } catch (\Exception $e) {
                $lastException = $e;
                $attempt++;
                
                Log::warning("MCP generation attempt {$attempt} failed", [
                    'error' => $e->getMessage(),
                    'prompt' => $prompt,
                ]);

                if ($attempt < $this->maxRetries) {
                    usleep($this->retryDelay * 1000);
                }
            }
        }

        throw new MCPException(
            "MCP generation failed after {$this->maxRetries} attempts: " . 
            ($lastException ? $lastException->getMessage() : 'Unknown error')
        );
    }

    /**
     * Get HTTP headers for MCP requests
     */
    protected function getHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($this->apiKey) {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        }

        return $headers;
    }

    /**
     * Get whitelisted Tailwind classes for the given style level
     */
    protected function getWhitelistedClasses(string $styleLevel): array
    {
        $whitelist = config('tailwind-whitelist');
        $classes = [];

        foreach ($whitelist as $category => $levels) {
            if (isset($levels[$styleLevel])) {
                $classes = array_merge($classes, $levels[$styleLevel]);
            }
        }

        return array_unique($classes);
    }

    /**
     * Parse the Anthropic API response
     */
    protected function parseAnthropicResponse(array $response): string
    {
        if (!isset($response['content'][0]['text'])) {
            throw new MCPException('Invalid Anthropic response: missing content');
        }

        $text = $response['content'][0]['text'];
        
        // Extract HTML if wrapped in code blocks
        if (preg_match('/```html\s*(.*?)\s*```/s', $text, $matches)) {
            return trim($matches[1]);
        }
        
        if (preg_match('/```\s*(.*?)\s*```/s', $text, $matches)) {
            return trim($matches[1]);
        }
        
        return trim($text);
    }

    /**
     * Parse the MCP server response (legacy)
     */
    protected function parseResponse(array $response): string
    {
        if (!isset($response['html'])) {
            throw new MCPException('Invalid MCP response: missing html field');
        }

        return $response['html'];
    }

    /**
     * Health check for Anthropic API
     */
    public function healthCheck(): bool
    {
        try {
            // Test Anthropic API with a minimal request
            $response = Http::timeout(10)
                ->withHeaders([
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => '2023-06-01',
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model' => config('mcp.anthropic.model'),
                    'max_tokens' => 10,
                    'messages' => [[
                        'role' => 'user',
                        'content' => 'Hi',
                    ]],
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Anthropic API health check failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
