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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->comment('Owner - NULL for system brands');
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete()->comment('For multi-tenant setups');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('industry', 100)->nullable();
            $table->json('target_audience')->nullable()->comment('Audience segments');
            $table->text('value_proposition')->nullable();
            $table->json('brand_personality')->nullable()->comment('Personality traits, values');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_template')->default(false)->comment('Template brands for quick start');
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
