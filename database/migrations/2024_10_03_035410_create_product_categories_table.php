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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->uuid('id')
                ->primary();

            $table->string('slug', 100)
                ->unique();

            $table->jsonb('title');

            $table->uuid('image_id')
                ->nullable();
            $table->foreign('image_id')
                ->references('id')
                ->on('media_storage');

            $table->boolean('is_published')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
