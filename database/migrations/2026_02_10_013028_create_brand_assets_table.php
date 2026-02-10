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
        Schema::create('brand_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->enum('asset_type', ['logo_primary', 'logo_secondary', 'logo_icon', 'image', 'font', 'icon', 'favicon', 'other']);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('file_path', 500)->comment('Storage path or URL');
            $table->string('mime_type', 100)->nullable();
            $table->integer('file_size')->nullable()->comment('In bytes');
            $table->json('dimensions')->nullable()->comment('{width: 100, height: 100}');
            $table->json('metadata')->nullable()->comment('Additional asset-specific data');
            $table->boolean('is_primary')->default(false)->comment('Primary asset for this type');
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->index(['brand_id', 'asset_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_assets');
    }
};
