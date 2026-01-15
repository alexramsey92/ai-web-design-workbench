<?php

namespace App\Services\Generation;

use App\Services\MCP\MCPClient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class HTMLGenerator
{
    protected MCPClient $mcpClient;
    protected array $styleLevel = ['full', 'mid', 'low'];

    public function __construct(MCPClient $mcpClient)
    {
        $this->mcpClient = $mcpClient;
    }

    /**
     * Generate HTML based on type and options
     */
    public function generate(string $type, array $options = []): string
    {
        $this->validateOptions($type, $options);
        
        $styleLevel = $options['style_level'] ?? config('mcp.default_style_level', 'full');
        
        // If MCP is enabled, use AI generation
        if ($this->mcpClient->isEnabled()) {
            return $this->generateWithMCP($type, $options, $styleLevel);
        }
        
        // Fallback to template-based generation
        return $this->generateFromTemplate($type, $options, $styleLevel);
    }

    /**
     * Generate HTML using MCP (AI-powered)
     */
    protected function generateWithMCP(string $type, array $options, string $styleLevel): string
    {
        $prompt = $this->buildPrompt($type, $options);
        
        $context = [
            'type' => $type,
            'style_level' => $styleLevel,
            'options' => $options,
        ];
        
        return $this->mcpClient->generate($prompt, $context);
    }

    /**
     * Generate HTML from predefined templates (fallback)
     */
    protected function generateFromTemplate(string $type, array $options, string $styleLevel): string
    {
        $generator = $this->getTemplateGenerator($type);
        
        return $generator->generate($options, $styleLevel);
    }

    /**
     * Build prompt for MCP generation
     */
    protected function buildPrompt(string $type, array $options): string
    {
        $systemPrompt = config('mcp.prompts.system');
        $typePrompts = config("mcp.prompts.{$type}", []);
        
        $prompt = $systemPrompt . "\n\n";
        
        foreach ($options as $key => $value) {
            if (isset($typePrompts[$key])) {
                $sectionPrompt = $typePrompts[$key];
                
                // Replace placeholders
                foreach ($options as $optionKey => $optionValue) {
                    $placeholder = '{' . $optionKey . '}';
                    if (is_string($optionValue)) {
                        $sectionPrompt = str_replace($placeholder, $optionValue, $sectionPrompt);
                    }
                }
                
                $prompt .= $sectionPrompt . "\n";
            }
        }
        
        return $prompt;
    }

    /**
     * Get the appropriate template generator
     */
    protected function getTemplateGenerator(string $type): object
    {
        // Use semantic generator by default
        $useSemanticClasses = config('mcp.use_semantic_classes', true);
        
        $suffix = $useSemanticClasses ? 'SemanticGenerator' : 'Generator';
        $className = 'App\\Services\\Templates\\' . studly_case($type) . $suffix;
        
        // Fallback to non-semantic if semantic doesn't exist
        if (!class_exists($className)) {
            $className = 'App\\Services\\Templates\\' . studly_case($type) . 'Generator';
        }
        
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Template generator for type '{$type}' not found");
        }
        
        return new $className();
    }

    /**
     * Validate generation options
     */
    protected function validateOptions(string $type, array $options): void
    {
        $rules = $this->getValidationRules($type);
        
        $validator = Validator::make($options, $rules);
        
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Get validation rules for the given type
     */
    protected function getValidationRules(string $type): array
    {
        $baseRules = [
            'style_level' => 'sometimes|in:full,mid,low',
        ];
        
        $typeRules = match($type) {
            'landing_page' => [
                'company_name' => 'sometimes|string|max:255',
                'industry' => 'sometimes|string|max:255',
                'sections' => 'sometimes|array',
                'sections.*' => 'in:hero,features,cta,testimonials,pricing,footer',
            ],
            default => [],
        };
        
        return array_merge($baseRules, $typeRules);
    }

    /**
     * Get available style levels
     */
    public function getStyleLevels(): array
    {
        return $this->styleLevel;
    }

    /**
     * Format HTML output (prettify)
     */
    public function formatHTML(string $html): string
    {
        // Basic HTML formatting
        $formatted = preg_replace('/>\s+</', ">\n<", $html);
        
        // Indent nested elements
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $dom->formatOutput = true;
        
        return $dom->saveHTML() ?: $html;
    }

    /**
     * Validate generated HTML against guardrails
     */
    public function validateHTML(string $html): array
    {
        $issues = [];
        $guardrails = config('mcp.guardrails');
        
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Check nesting depth
        $maxDepth = $this->getMaxNestingDepth($dom->documentElement);
        if ($maxDepth > $guardrails['max_nesting_depth']) {
            $issues[] = "Nesting depth ({$maxDepth}) exceeds maximum ({$guardrails['max_nesting_depth']})";
        }
        
        // Check element count
        $elementCount = $dom->getElementsByTagName('*')->length;
        if ($elementCount > $guardrails['max_element_count']) {
            $issues[] = "Element count ({$elementCount}) exceeds maximum ({$guardrails['max_element_count']})";
        }
        
        // Check for disallowed tags
        if ($guardrails['allowed_tags']) {
            $allElements = $dom->getElementsByTagName('*');
            foreach ($allElements as $element) {
                if (!in_array($element->nodeName, $guardrails['allowed_tags'])) {
                    $issues[] = "Disallowed tag found: {$element->nodeName}";
                }
            }
        }
        
        return $issues;
    }

    /**
     * Get maximum nesting depth of DOM tree
     */
    protected function getMaxNestingDepth(\DOMNode $node, int $depth = 0): int
    {
        $maxDepth = $depth;
        
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $childDepth = $this->getMaxNestingDepth($child, $depth + 1);
                    $maxDepth = max($maxDepth, $childDepth);
                }
            }
        }
        
        return $maxDepth;
    }
}
