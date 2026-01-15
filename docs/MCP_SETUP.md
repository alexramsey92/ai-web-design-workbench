# MCP Server Setup Guide

This guide will help you set up a Model Context Protocol (MCP) server for AI-powered HTML generation with the AI Web Design Workbench.

## What is MCP?

The Model Context Protocol (MCP) enables communication between your application and AI models. When configured, it allows the workbench to generate HTML using AI while respecting the curated Tailwind class whitelist.

## Do I Need MCP?

**No!** The workbench works perfectly without MCP using built-in templates. MCP is optional and provides AI-powered generation as an enhancement.

## Setting Up Laravel Boost MCP

### Option 1: Use an Existing MCP Server

If you already have a Laravel Boost MCP server running:

1. Edit your `.env` file:
   ```env
   MCP_ENABLED=true
   MCP_SERVER_URL=http://your-server:3000
   MCP_API_KEY=your-key-here  # If required
   ```

2. Test the connection:
   ```bash
   php artisan mcp:status
   ```

### Option 2: Set Up Your Own MCP Server

1. **Clone the Laravel Boost MCP repository**
   ```bash
   git clone https://github.com/laravel-boost/mcp.git
   cd mcp
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Configure the server**
   
   Create a `.env` file:
   ```env
   PORT=3000
   OPENAI_API_KEY=your-openai-key
   ```

4. **Start the server**
   ```bash
   npm start
   ```

5. **Configure the workbench**
   
   In your workbench `.env`:
   ```env
   MCP_ENABLED=true
   MCP_SERVER_URL=http://localhost:3000
   ```

6. **Verify connection**
   ```bash
   php artisan mcp:status
   ```

## Alternative MCP Servers

You can use any MCP-compatible server. The workbench expects:

### Request Format

```json
{
  "prompt": "System prompt + user requirements",
  "context": {
    "type": "landing-page",
    "style_level": "full",
    "options": {}
  },
  "whitelist": ["array", "of", "allowed", "classes"],
  "guardrails": {
    "max_nesting_depth": 10,
    "max_element_count": 500,
    "allowed_tags": ["div", "section", ...]
  }
}
```

### Response Format

```json
{
  "html": "<section>...generated HTML...</section>"
}
```

### Health Check Endpoint

Your MCP server should respond to `GET /health` with a 200 status code.

## Troubleshooting

### Connection Refused

- Ensure your MCP server is running
- Check that the port matches your configuration
- Verify firewall settings

### Authentication Errors

- Confirm your `MCP_API_KEY` is correct
- Check that your MCP server is configured for authentication

### Timeout Issues

Increase the timeout in your `.env`:
```env
MCP_TIMEOUT=60
```

### Server Not Responding

```bash
# Check MCP status
php artisan mcp:status

# Test with curl
curl http://localhost:3000/health
```

## Disabling MCP

To disable MCP and use template-based generation:

```env
MCP_ENABLED=false
```

## Security Considerations

- Run MCP servers on private networks
- Use API keys for production deployments
- Keep your API keys secure and never commit them
- Consider rate limiting for public-facing servers

## Performance Tips

- Host your MCP server close to your application
- Use caching for repeated generations
- Monitor response times and adjust timeout settings
- Consider horizontal scaling for high-volume usage

## Getting Help

- Check the [Laravel Boost MCP documentation](https://github.com/laravel-boost/mcp)
- Review the workbench's MCP integration in `app/Services/MCP/`
- Open an issue if you encounter problems

---

Remember: MCP is optional. The workbench provides excellent template-based generation out of the box!
