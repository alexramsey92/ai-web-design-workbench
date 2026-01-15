<?php

namespace App\Services\MCP;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Exceptions\MCPException;

class MCPClient
{
    protected string $serverUrl;
    protected ?string $apiKey;
    protected int $timeout;
    protected int $maxRetries;
    protected int $retryDelay;

    public function __construct()
    {
        $this->serverUrl = config('mcp.server_url');
        $this->apiKey = config('mcp.api_key');
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
                $response = Http::timeout($this->timeout)
                    ->withHeaders($this->getHeaders())
                    ->post($this->serverUrl . '/generate', [
                        'prompt' => $prompt,
                        'context' => $context,
                        'whitelist' => $this->getWhitelistedClasses($context['style_level'] ?? 'full'),
                        'guardrails' => config('mcp.guardrails'),
                    ]);

                if ($response->successful()) {
                    return $this->parseResponse($response->json());
                }

                throw new MCPException(
                    "MCP server responded with status {$response->status()}: {$response->body()}"
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
     * Parse the MCP server response
     */
    protected function parseResponse(array $response): string
    {
        if (!isset($response['html'])) {
            throw new MCPException('Invalid MCP response: missing html field');
        }

        return $response['html'];
    }

    /**
     * Health check for MCP server
     */
    public function healthCheck(): bool
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders($this->getHeaders())
                ->get($this->serverUrl . '/health');

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('MCP health check failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
