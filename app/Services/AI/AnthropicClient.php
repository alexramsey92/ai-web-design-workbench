<?php

namespace App\Services\AI;

use App\Exceptions\MCPException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        return config('mcp.enabled', false) && ! empty($this->serverUrl);
    }

    /**
     * Generate HTML content via MCP server
     */
    public function generate(string $prompt, array $context = []): string
    {
        if (! $this->isEnabled()) {
            throw new MCPException('MCP is not enabled or configured. Check your .env settings.');
        }

        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->maxRetries) {
            try {
                $whitelistedClasses = $this->getWhitelistedClasses($context['style_level'] ?? 'full');

                // Build comprehensive system prompt based on guide
                $systemPrompt = $this->buildSystemPrompt($whitelistedClasses, $context);

                // Build user prompt with the actual request
                $userPrompt = $this->buildUserPrompt($prompt);

                $payload = [
                    'model' => config('mcp.anthropic.model'),
                    'max_tokens' => (int) ($context['max_tokens'] ?? ($context['options']['max_tokens'] ?? config('mcp.anthropic.max_tokens', 4096))),
                    'temperature' => (float) config('mcp.anthropic.temperature', 0.7),
                    'system' => $systemPrompt,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $userPrompt,
                        ],
                    ],
                ];

                // Record the request payload for debugging/inspection
                $this->lastRequest = [
                    'payload' => $payload,
                    'headers' => [
                        'x-api-key' => $this->apiKey,
                        'anthropic-version' => '2023-06-01',
                        'Content-Type' => 'application/json',
                    ],
                    'prompt' => $userPrompt,
                    'context' => $context,
                ];

                $start = microtime(true);

                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'x-api-key' => $this->apiKey,
                        'anthropic-version' => '2023-06-01',
                        'Content-Type' => 'application/json',
                    ])
                    ->post('https://api.anthropic.com/v1/messages', $payload);

                $duration = microtime(true) - $start;

                // Record raw response info
                $this->lastResponse = [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'json' => $response->successful() ? $response->json() : null,
                    'duration_ms' => (int) ($duration * 1000),
                ];

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

        // On final failure, record the last exception and make lastResponse include the error
        $this->lastResponse = [
            'status' => null,
            'body' => $lastException ? $lastException->getMessage() : 'Unknown error',
            'json' => null,
            'duration_ms' => null,
        ];

        throw new MCPException(
            "MCP generation failed after {$this->maxRetries} attempts: ".
            ($lastException ? $lastException->getMessage() : 'Unknown error')
        );
    }

    /**
     * Build system prompt with semantic classes and rules
     */
    protected function buildSystemPrompt(array $whitelistedClasses, array $context): string
    {
        $semanticClasses = ['hero', 'section', 'feature-grid', 'feature-card', 'cta-button', 'btn-primary', 'btn-secondary', 'prose', 'card'];

        $prompt = "You are an expert web designer creating beautiful, modern landing pages.\n\n";
        $prompt .= "RULES:\n";
        $prompt .= "- Output clean, semantic HTML only\n";
        $prompt .= "- Mix Tailwind utilities with semantic classes\n";
        $prompt .= '- Use these semantic CSS classes: '.implode(', ', $semanticClasses)."\n";
        $prompt .= '- Available Tailwind classes: '.implode(', ', array_slice($whitelistedClasses, 0, 100))." (and more)\n";
        $prompt .= "- Do NOT include <!DOCTYPE>, <html>, <head>, or <body> tags - only the content\n";
        $prompt .= "- No inline JavaScript or CSS\n";
        $prompt .= "- Use semantic HTML5 tags (section, article, header, nav, etc.)\n";
        $prompt .= "- Include Font Awesome icons where appropriate (use <i class=\"fas fa-icon-name\"></i>)\n\n";

        $prompt .= "STRUCTURE:\n";
        $prompt .= "1. Hero section with compelling headline, subheading, and CTA button\n";
        $prompt .= "2. Feature grid showcasing 3-4 key benefits with icons\n";
        $prompt .= "3. Optional secondary content section\n";
        $prompt .= "4. Final CTA section\n\n";

        $prompt .= "EXAMPLE STRUCTURE:\n";
        $prompt .= "<section class=\"hero\">\n";
        $prompt .= "    <h1 class=\"text-5xl font-bold mb-4\">Your Headline</h1>\n";
        $prompt .= "    <p class=\"text-xl mb-8\">Compelling subheading</p>\n";
        $prompt .= "    <a href=\"#\" class=\"cta-button\">Get Started</a>\n";
        $prompt .= "</section>\n\n";
        $prompt .= "<section class=\"section\">\n";
        $prompt .= "    <h2 class=\"text-3xl font-bold text-center mb-12\">Features</h2>\n";
        $prompt .= "    <div class=\"feature-grid\">\n";
        $prompt .= "        <div class=\"feature-card\">\n";
        $prompt .= "            <i class=\"fas fa-bolt text-blue-600 text-4xl mb-4\"></i>\n";
        $prompt .= "            <h3 class=\"text-xl font-semibold mb-2\">Feature Title</h3>\n";
        $prompt .= "            <p class=\"text-gray-600\">Description</p>\n";
        $prompt .= "        </div>\n";
        $prompt .= "    </div>\n";
        $prompt .= "</section>\n";

        return $prompt;
    }

    /**
     * Build user prompt with the actual request
     */
    protected function buildUserPrompt(string $userRequest): string
    {
        return "Create a complete, professional landing page for: {$userRequest}\n\n".
               'Make it visually appealing, modern, and conversion-focused. '.
               'Use appropriate colors, spacing, and typography. '.
               'Include relevant icons and imagery suggestions. '.
               'Remember: output ONLY the HTML content (no DOCTYPE, html, head, or body tags).';
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
            $headers['Authorization'] = 'Bearer '.$this->apiKey;
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
        if (! isset($response['content'][0]['text'])) {
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
        if (! isset($response['html'])) {
            throw new MCPException('Invalid MCP response: missing html field');
        }

        return $response['html'];
    }

    /**
     * Health check for Anthropic API
     */
    protected ?array $lastRequest = null;

    protected ?array $lastResponse = null;

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

    /**
     * Get information about the last Anthropic request/response
     */
    public function getLastCallInfo(): ?array
    {
        return [
            'request' => $this->lastRequest,
            'response' => $this->lastResponse,
        ];
    }
}
