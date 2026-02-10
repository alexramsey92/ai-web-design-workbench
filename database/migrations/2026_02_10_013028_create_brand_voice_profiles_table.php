<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brand_voice_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            
            // Voice Characteristics
            $table->enum('tone', ['professional', 'casual', 'friendly', 'authoritative', 'playful', 'serious', 'inspirational'])->default('professional');
            $table->enum('formality', ['very_formal', 'formal', 'neutral', 'casual', 'very_casual'])->default('neutral');
            $table->enum('enthusiasm', ['low', 'moderate', 'high'])->default('moderate');
            
            // Writing Style
            $table->enum('preferred_person', ['first', 'second', 'third', 'mixed'])->default('second')->comment('I/We, You, They');
            $table->enum('sentence_length', ['short', 'medium', 'long', 'varied'])->default('varied');
            $table->boolean('use_contractions')->default(true);
            $table->boolean('use_emojis')->default(false);
            $table->boolean('use_technical_jargon')->default(false);
            
            // Vocabulary
            $table->json('preferred_terms')->nullable()->comment('Array of preferred words/phrases');
            $table->json('avoid_terms')->nullable()->comment('Array of words to avoid');
            $table->json('brand_specific_terms')->nullable()->comment('Product names, taglines, etc.');
            
            // Messaging
            $table->text('key_messages')->nullable()->comment('Core messages to reinforce');
            $table->json('value_props')->nullable()->comment('Key value propositions');
            
            $table->timestamps();
            
            $table->unique('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_voice_profiles');
    }
};
