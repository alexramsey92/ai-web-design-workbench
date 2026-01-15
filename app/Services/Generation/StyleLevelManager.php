<?php

namespace App\Services\Generation;

class StyleLevelManager
{
    protected array $levels = [
        'full' => [
            'name' => 'Full Styling',
            'description' => 'Maximum styling with all Tailwind utilities, animations, and effects',
            'class_density' => 'high',
            'features' => [
                'gradients',
                'shadows',
                'transitions',
                'hover-effects',
                'responsive-variants',
                'custom-colors',
            ],
        ],
        'mid' => [
            'name' => 'Mid Styling',
            'description' => 'Balanced styling with essential utilities and moderate effects',
            'class_density' => 'medium',
            'features' => [
                'basic-colors',
                'simple-shadows',
                'essential-transitions',
                'core-responsive',
            ],
        ],
        'low' => [
            'name' => 'Low Styling',
            'description' => 'Minimal styling with only core utilities for clean, simple designs',
            'class_density' => 'low',
            'features' => [
                'basic-layout',
                'typography',
                'minimal-colors',
            ],
        ],
    ];

    /**
     * Get all available style levels
     */
    public function all(): array
    {
        return $this->levels;
    }

    /**
     * Get a specific style level
     */
    public function get(string $level): ?array
    {
        return $this->levels[$level] ?? null;
    }

    /**
     * Check if a style level exists
     */
    public function exists(string $level): bool
    {
        return isset($this->levels[$level]);
    }

    /**
     * Get classes for a specific level from whitelist
     */
    public function getClassesForLevel(string $level): array
    {
        if (!$this->exists($level)) {
            return [];
        }

        $whitelist = config('tailwind-whitelist');
        $classes = [];

        foreach ($whitelist as $category => $levels) {
            if (isset($levels[$level])) {
                $classes[$category] = $levels[$level];
            }
        }

        return $classes;
    }

    /**
     * Get flattened array of all classes for a level
     */
    public function getFlattenedClasses(string $level): array
    {
        $classes = $this->getClassesForLevel($level);
        $flattened = [];

        foreach ($classes as $category => $categoryClasses) {
            $flattened = array_merge($flattened, $categoryClasses);
        }

        return array_unique($flattened);
    }

    /**
     * Compare two style levels
     */
    public function compare(string $level1, string $level2): int
    {
        $order = ['low' => 1, 'mid' => 2, 'full' => 3];
        
        $value1 = $order[$level1] ?? 0;
        $value2 = $order[$level2] ?? 0;
        
        return $value1 <=> $value2;
    }

    /**
     * Get the default style level
     */
    public function getDefault(): string
    {
        return config('mcp.default_style_level', 'full');
    }
}
