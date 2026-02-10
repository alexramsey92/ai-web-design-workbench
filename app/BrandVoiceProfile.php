<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandVoiceProfile extends Model
{
    protected $fillable = [
        'brand_id',
        'tone',
        'formality',
        'enthusiasm',
        'preferred_person',
        'sentence_length',
        'use_contractions',
        'use_emojis',
        'use_technical_jargon',
        'preferred_terms',
        'avoid_terms',
        'brand_specific_terms',
        'key_messages',
        'value_props',
    ];

    protected function casts(): array
    {
        return [
            'use_contractions' => 'boolean',
            'use_emojis' => 'boolean',
            'use_technical_jargon' => 'boolean',
            'preferred_terms' => 'array',
            'avoid_terms' => 'array',
            'brand_specific_terms' => 'array',
            'value_props' => 'array',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get voice characteristics as a formatted string
     */
    public function getVoiceDescription(): string
    {
        return "{$this->tone}, {$this->formality}, {$this->enthusiasm} enthusiasm";
    }

    /**
     * Get AI prompt instructions based on voice profile
     */
    public function getAIInstructions(): string
    {
        $instructions = [];

        $instructions[] = "Tone: {$this->tone}";
        $instructions[] = "Formality: {$this->formality}";
        $instructions[] = "Enthusiasm: {$this->enthusiasm}";
        $instructions[] = "Use {$this->preferred_person} person perspective";
        $instructions[] = "Sentence length: {$this->sentence_length}";

        if ($this->use_contractions) {
            $instructions[] = "Use contractions (don't, can't, we're)";
        } else {
            $instructions[] = "Avoid contractions (do not, cannot, we are)";
        }

        if ($this->use_emojis) {
            $instructions[] = "You may use emojis appropriately";
        }

        if ($this->use_technical_jargon) {
            $instructions[] = "Technical jargon is acceptable";
        } else {
            $instructions[] = "Avoid technical jargon, use plain language";
        }

        if (!empty($this->preferred_terms)) {
            $terms = implode(', ', $this->preferred_terms);
            $instructions[] = "Preferred terms: {$terms}";
        }

        if (!empty($this->avoid_terms)) {
            $terms = implode(', ', $this->avoid_terms);
            $instructions[] = "Avoid these terms: {$terms}";
        }

        if ($this->key_messages) {
            $instructions[] = "Key messages to reinforce: {$this->key_messages}";
        }

        return implode("\n", $instructions);
    }
}
