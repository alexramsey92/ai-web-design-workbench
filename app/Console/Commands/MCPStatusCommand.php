<?php

namespace App\Console\Commands;

use App\Services\AI\AnthropicClient;
use Illuminate\Console\Command;

class MCPStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'ai:status';

    /**
     * The console command description.
     */
    protected $description = 'Check AI content generation status and Anthropic API connection';

    protected AnthropicClient $anthropicClient;

    /**
     * Create a new command instance.
     */
    public function __construct(AnthropicClient $anthropicClient)
    {
        parent::__construct();
        $this->mcpClient = $mcpClient;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('AI Content Generation Status Check');
        $this->line('');

        // Check if AI is enabled
        if (!config('mcp.enabled')) {
            $this->warn('⚠ AI Content Generation is DISABLED');
            $this->line('');
            $this->line('To enable AI generation:');
            $this->line('1. Set AI_CONTENT_GENERATION_ENABLED=true in your .env file');
            $this->line('2. Set ANTHROPIC_API_KEY with your Claude API key');
            $this->line('3. Configure model and generation settings as needed');
            $this->line('');
            $this->info('When AI is disabled, template-based generation will be used as fallback.');
            return 0;
        }

        $this->info('✓ AI Content Generation is ENABLED');
        $this->line('');

        // Display configuration
        $this->line('Configuration:');
        $this->line('  Provider: ' . config('mcp.provider'));
        $this->line('  Model: ' . config('mcp.anthropic.model'));
        $this->line('  API Key: ' . (config('mcp.anthropic.api_key') ? '(configured)' : '(not set)'));
        $this->line('  Max Tokens: ' . config('mcp.anthropic.max_tokens'));
        $this->line('  Temperature: ' . config('mcp.anthropic.temperature'));
        $this->line('  Timeout: ' . config('mcp.timeout') . 's');
        $this->line('  Rate Limiting: ' . (config('mcp.rate_limiting.enabled') ? 'Enabled' : 'Disabled'));
        if (config('mcp.rate_limiting.enabled')) {
            $this->line('    Max Requests/Hour: ' . config('mcp.rate_limiting.max_requests_per_hour'));
            $this->line('    Max Requests/Day: ' . config('mcp.rate_limiting.max_requests_per_day'));
        }
        $this->line('  Logging: ' . (config('mcp.logging_enabled') ? 'Enabled' : 'Disabled'));
        $this->line('');

        // Test connection
        $this->line('Testing Anthropic API connection...');
        
        try {
            $isHealthy = $this->mcpClient->healthCheck();
            
            if ($isHealthy) {
                $this->info('✓ Anthropic API is responding');
                $this->line('');
                $this->info('Your AI generation is ready to use!');
                return 0;
            } else {
                $this->error('✗ Anthropic API is not responding');
                $this->line('');
                $this->line('Troubleshooting:');
                $this->line('1. Verify your ANTHROPIC_API_KEY is correct in .env');
                $this->line('2. Check you have API credits available at console.anthropic.com');
                $this->line('3. Ensure your internet connection is working');
                $this->line('4. Check Anthropic API status at status.anthropic.com');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('✗ Connection failed: ' . $e->getMessage());
            return 1;
        }
    }
}
