<?php

namespace App\Console\Commands;

use App\Services\MCP\MCPClient;
use Illuminate\Console\Command;

class MCPStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'mcp:status';

    /**
     * The console command description.
     */
    protected $description = 'Check MCP server status and configuration';

    protected MCPClient $mcpClient;

    /**
     * Create a new command instance.
     */
    public function __construct(MCPClient $mcpClient)
    {
        parent::__construct();
        $this->mcpClient = $mcpClient;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('MCP Server Status Check');
        $this->line('');

        // Check if MCP is enabled
        if (!config('mcp.enabled')) {
            $this->warn('⚠ MCP is DISABLED');
            $this->line('');
            $this->line('To enable MCP:');
            $this->line('1. Set MCP_ENABLED=true in your .env file');
            $this->line('2. Configure MCP_SERVER_URL (default: http://localhost:3000)');
            $this->line('3. Optionally set MCP_API_KEY if your server requires authentication');
            $this->line('');
            $this->info('When MCP is disabled, template-based generation will be used as fallback.');
            return 0;
        }

        $this->info('✓ MCP is ENABLED');
        $this->line('');

        // Display configuration
        $this->line('Configuration:');
        $this->line('  Server URL: ' . config('mcp.server_url'));
        $this->line('  API Key: ' . (config('mcp.api_key') ? '(configured)' : '(not set)'));
        $this->line('  Timeout: ' . config('mcp.timeout') . 's');
        $this->line('  Max Retries: ' . config('mcp.max_retries'));
        $this->line('');

        // Test connection
        $this->line('Testing connection...');
        
        try {
            $isHealthy = $this->mcpClient->healthCheck();
            
            if ($isHealthy) {
                $this->info('✓ MCP server is responding');
                return 0;
            } else {
                $this->error('✗ MCP server is not responding');
                $this->line('');
                $this->line('Troubleshooting:');
                $this->line('1. Ensure your MCP server is running');
                $this->line('2. Verify the server URL is correct');
                $this->line('3. Check that the server is accessible from this machine');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('✗ Connection failed: ' . $e->getMessage());
            return 1;
        }
    }
}
