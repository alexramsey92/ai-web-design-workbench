<?php

namespace App\Services\Branding;

use App\Brand;
use App\BrandVisualIdentity;
use App\BrandVoiceProfile;
use DOMDocument;
use DOMXPath;

class BrandValidator
{
    /**
     * Validate color usage in HTML
     */
    public function validateColors(Brand $brand, string $html): array
    {
        $brand->load('visualIdentity');

        if (!$brand->visualIdentity) {
            return [
                'status' => 'skip',
                'message' => 'No visual identity configured',
                'issues' => [],
            ];
        }

        $issues = [];
        $visual = $brand->visualIdentity;

        // Check if primary color is used
        $primaryColorUsed = $this->colorIsPresent($html, $visual->primary_color);
        if (!$primaryColorUsed) {
            $issues[] = [
                'severity' => 'warning',
                'message' => 'Primary brand color not detected in content',
                'suggestion' => "Use {$visual->primary_color} in prominent elements",
            ];
        }

        // Check for inconsistent color usage (colors not in brand palette)
        $nonBrandColors = $this->findNonBrandColors($html, $visual);
        if (!empty($nonBrandColors)) {
            foreach ($nonBrandColors as $color) {
                $issues[] = [
                    'severity' => 'warning',
                    'message' => "Non-brand color detected: {$color}",
                    'suggestion' => 'Consider using brand palette colors',
                ];
            }
        }

        return [
            'status' => empty($issues) ? 'pass' : 'warning',
            'issues' => $issues,
            'score' => max(0, 100 - (count($issues) * 10)),
        ];
    }

    /**
     * Validate typography usage in HTML
     */
    public function validateTypography(Brand $brand, string $html): array
    {
        $brand->load('visualIdentity');

        if (!$brand->visualIdentity) {
            return [
                'status' => 'skip',
                'message' => 'No visual identity configured',
                'issues' => [],
            ];
        }

        $issues = [];
        $visual = $brand->visualIdentity;

        // Check if brand fonts are referenced
        $headingFontPresent = stripos($html, $visual->heading_font) !== false;
        $bodyFontPresent = stripos($html, $visual->body_font) !== false;

        if (!$headingFontPresent && !$bodyFontPresent) {
            $issues[] = [
                'severity' => 'warning',
                'message' => 'Brand fonts not detected in content',
                'suggestion' => "Use {$visual->heading_font} for headings and {$visual->body_font} for body text",
            ];
        }

        // Check for proper heading hierarchy
        $headingIssues = $this->validateHeadingHierarchy($html);
        $issues = array_merge($issues, $headingIssues);

        return [
            'status' => empty($issues) ? 'pass' : 'warning',
            'issues' => $issues,
            'score' => max(0, 100 - (count($issues) * 15)),
        ];
    }

    /**
     * Validate brand voice in text content
     */
    public function validateVoice(Brand $brand, string $text): array
    {
        $brand->load('voiceProfile');

        if (!$brand->voiceProfile) {
            return [
                'status' => 'skip',
                'message' => 'No voice profile configured',
                'issues' => [],
            ];
        }

        $issues = [];
        $voice = $brand->voiceProfile;

        // Check for avoided terms
        if (!empty($voice->avoid_terms)) {
            foreach ($voice->avoid_terms as $term) {
                if (stripos($text, $term) !== false) {
                    $issues[] = [
                        'severity' => 'error',
                        'message' => "Content contains avoided term: '{$term}'",
                        'suggestion' => 'Remove or replace this term',
                    ];
                }
            }
        }

        // Check contractions usage
        $hasContractions = preg_match("/\b(don't|can't|won't|shouldn't|wouldn't|isn't|aren't|wasn't|weren't|hasn't|haven't|hadn't|doesn't)/i", $text);
        if ($hasContractions && !$voice->use_contractions) {
            $issues[] = [
                'severity' => 'warning',
                'message' => 'Content uses contractions, inconsistent with brand voice',
                'suggestion' => 'Use full forms (do not, cannot, etc.)',
            ];
        } elseif (!$hasContractions && $voice->use_contractions && strlen($text) > 100) {
            $issues[] = [
                'severity' => 'info',
                'message' => 'Consider using contractions for a more casual tone',
                'suggestion' => "Brand voice prefers contractions (don't vs do not)",
            ];
        }

        // Check for preferred terms usage
        if (!empty($voice->preferred_terms)) {
            $preferredTermsUsed = 0;
            foreach ($voice->preferred_terms as $term) {
                if (stripos($text, $term) !== false) {
                    $preferredTermsUsed++;
                }
            }

            if ($preferredTermsUsed === 0 && count($voice->preferred_terms) > 0) {
                $terms = implode(', ', array_slice($voice->preferred_terms, 0, 3));
                $issues[] = [
                    'severity' => 'info',
                    'message' => 'Consider using preferred brand terms',
                    'suggestion' => "Preferred terms: {$terms}",
                ];
            }
        }

        // Check tone (simplified - would use AI in production)
        $toneIssues = $this->analyzeTone($text, $voice);
        $issues = array_merge($issues, $toneIssues);

        return [
            'status' => $this->determineStatus($issues),
            'issues' => $issues,
            'score' => max(0, 100 - (count($issues) * 10)),
        ];
    }

    /**
     * Validate accessibility compliance
     */
    public function validateAccessibility(string $html): array
    {
        $issues = [];

        // Check for alt text on images
        $doc = $this->loadHTML($html);
        if ($doc) {
            $xpath = new DOMXPath($doc);

            // Images without alt text
            $imagesWithoutAlt = $xpath->query('//img[not(@alt) or @alt=""]');
            if ($imagesWithoutAlt->length > 0) {
                $issues[] = [
                    'severity' => 'error',
                    'message' => "{$imagesWithoutAlt->length} image(s) missing alt text",
                    'suggestion' => 'Add descriptive alt text to all images',
                ];
            }

            // Links without text
            $emptyLinks = $xpath->query('//a[not(normalize-space(.))]');
            if ($emptyLinks->length > 0) {
                $issues[] = [
                    'severity' => 'error',
                    'message' => "{$emptyLinks->length} link(s) without text content",
                    'suggestion' => 'Add descriptive text or aria-label to links',
                ];
            }

            // Form inputs without labels
            $inputsWithoutLabels = $xpath->query('//input[@type!="hidden" and not(@aria-label) and not(@id=//label/@for)]');
            if ($inputsWithoutLabels->length > 0) {
                $issues[] = [
                    'severity' => 'warning',
                    'message' => "{$inputsWithoutLabels->length} form input(s) without labels",
                    'suggestion' => 'Add <label> elements or aria-label attributes',
                ];
            }

            // Check for heading structure
            $h1Count = $xpath->query('//h1')->length;
            if ($h1Count === 0) {
                $issues[] = [
                    'severity' => 'warning',
                    'message' => 'No H1 heading found',
                    'suggestion' => 'Add a main H1 heading to the page',
                ];
            } elseif ($h1Count > 1) {
                $issues[] = [
                    'severity' => 'warning',
                    'message' => "Multiple H1 headings found ({$h1Count})",
                    'suggestion' => 'Use only one H1 per page',
                ];
            }
        }

        return [
            'status' => $this->determineStatus($issues),
            'issues' => $issues,
            'score' => max(0, 100 - (count($issues) * 15)),
        ];
    }

    /**
     * Get comprehensive validation report
     */
    public function getValidationReport(Brand $brand, string $html, string $text): array
    {
        $colorValidation = $this->validateColors($brand, $html);
        $typographyValidation = $this->validateTypography($brand, $html);
        $voiceValidation = $this->validateVoice($brand, $text);
        $accessibilityValidation = $this->validateAccessibility($html);

        // Calculate overall score
        $scores = [];
        if ($colorValidation['status'] !== 'skip') {
            $scores[] = $colorValidation['score'];
        }
        if ($typographyValidation['status'] !== 'skip') {
            $scores[] = $typographyValidation['score'];
        }
        if ($voiceValidation['status'] !== 'skip') {
            $scores[] = $voiceValidation['score'];
        }
        $scores[] = $accessibilityValidation['score'];

        $overallScore = !empty($scores) ? (int) round(array_sum($scores) / count($scores)) : 0;

        return [
            'overall_score' => $overallScore,
            'overall_status' => $overallScore >= 80 ? 'pass' : ($overallScore >= 60 ? 'warning' : 'fail'),
            'categories' => [
                'colors' => $colorValidation,
                'typography' => $typographyValidation,
                'voice' => $voiceValidation,
                'accessibility' => $accessibilityValidation,
            ],
            'total_issues' => array_sum([
                count($colorValidation['issues'] ?? []),
                count($typographyValidation['issues'] ?? []),
                count($voiceValidation['issues'] ?? []),
                count($accessibilityValidation['issues'] ?? []),
            ]),
        ];
    }

    /**
     * Check if a color is present in HTML (hex or class name)
     */
    protected function colorIsPresent(string $html, string $color): bool
    {
        return stripos($html, $color) !== false;
    }

    /**
     * Find colors in HTML that aren't in the brand palette
     */
    protected function findNonBrandColors(string $html, BrandVisualIdentity $visual): array
    {
        $brandColors = $visual->getColorsArray();
        $nonBrandColors = [];

        // Extract hex colors from HTML
        preg_match_all('/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})\b/', $html, $matches);

        foreach ($matches[0] as $color) {
            $normalizedColor = strtoupper($color);
            $isBrandColor = false;

            foreach ($brandColors as $brandColor) {
                if ($brandColor && strtoupper($brandColor) === $normalizedColor) {
                    $isBrandColor = true;
                    break;
                }
            }

            if (!$isBrandColor && !in_array($normalizedColor, $nonBrandColors)) {
                $nonBrandColors[] = $normalizedColor;
            }
        }

        return $nonBrandColors;
    }

    /**
     * Validate heading hierarchy
     */
    protected function validateHeadingHierarchy(string $html): array
    {
        $issues = [];
        $doc = $this->loadHTML($html);

        if (!$doc) {
            return $issues;
        }

        $xpath = new DOMXPath($doc);
        $headings = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');

        $previousLevel = 0;
        foreach ($headings as $heading) {
            $currentLevel = (int) substr($heading->nodeName, 1);

            if ($currentLevel > $previousLevel + 1) {
                $issues[] = [
                    'severity' => 'warning',
                    'message' => "Heading hierarchy skip detected (H{$previousLevel} to H{$currentLevel})",
                    'suggestion' => 'Maintain sequential heading levels',
                ];
            }

            $previousLevel = $currentLevel;
        }

        return $issues;
    }

    /**
     * Analyze tone of text (simplified version)
     */
    protected function analyzeTone(string $text, BrandVoiceProfile $voice): array
    {
        $issues = [];

        // Simple heuristics for tone analysis
        // In production, this would use AI/NLP

        // Check for exclamation marks (enthusiasm)
        $exclamationCount = substr_count($text, '!');
        $sentenceCount = max(1, substr_count($text, '.') + substr_count($text, '!') + substr_count($text, '?'));
        $exclamationRatio = $exclamationCount / $sentenceCount;

        if ($voice->enthusiasm === 'low' && $exclamationRatio > 0.2) {
            $issues[] = [
                'severity' => 'info',
                'message' => 'High use of exclamation marks detected',
                'suggestion' => 'Brand voice prefers low enthusiasm - consider reducing exclamation marks',
            ];
        } elseif ($voice->enthusiasm === 'high' && $exclamationRatio < 0.05) {
            $issues[] = [
                'severity' => 'info',
                'message' => 'Low use of exclamation marks detected',
                'suggestion' => 'Brand voice prefers high enthusiasm - consider adding more energy',
            ];
        }

        return $issues;
    }

    /**
     * Load HTML into DOMDocument
     */
    protected function loadHTML(string $html): ?DOMDocument
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);

        // Try to load the HTML
        $success = $doc->loadHTML('<?xml encoding="utf-8" ?>'.$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_clear_errors();

        return $success ? $doc : null;
    }

    /**
     * Determine overall status from issues
     */
    protected function determineStatus(array $issues): string
    {
        if (empty($issues)) {
            return 'pass';
        }

        $hasErrors = collect($issues)->contains('severity', 'error');
        $hasWarnings = collect($issues)->contains('severity', 'warning');

        if ($hasErrors) {
            return 'fail';
        }

        if ($hasWarnings) {
            return 'warning';
        }

        return 'pass';
    }
}
