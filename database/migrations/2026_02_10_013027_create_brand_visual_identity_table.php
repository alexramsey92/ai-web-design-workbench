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
        Schema::create('brand_visual_identity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            
            // Colors (hex values)
            $table->string('primary_color', 7)->default('#3B82F6');
            $table->string('secondary_color', 7)->nullable();
            $table->string('accent_color', 7)->nullable();
            $table->string('success_color', 7)->default('#10B981');
            $table->string('warning_color', 7)->default('#F59E0B');
            $table->string('error_color', 7)->default('#EF4444');
            $table->string('neutral_50', 7)->default('#F9FAFB');
            $table->string('neutral_100', 7)->default('#F3F4F6');
            $table->string('neutral_900', 7)->default('#111827');
            
            // Typography
            $table->string('heading_font')->default('Inter');
            $table->text('heading_font_url')->nullable()->comment('Google Fonts URL or CDN');
            $table->string('body_font')->default('Inter');
            $table->text('body_font_url')->nullable();
            $table->string('code_font')->default('JetBrains Mono');
            
            // Spacing & Layout
            $table->integer('spacing_unit')->default(4)->comment('Base spacing unit in px');
            $table->integer('border_radius_sm')->default(4);
            $table->integer('border_radius_md')->default(8);
            $table->integer('border_radius_lg')->default(12);
            
            // Shadows & Effects
            $table->boolean('use_shadows')->default(true);
            $table->boolean('use_gradients')->default(true);
            $table->boolean('use_animations')->default(true);
            
            $table->timestamps();
            
            $table->unique('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_visual_identity');
    }
};
