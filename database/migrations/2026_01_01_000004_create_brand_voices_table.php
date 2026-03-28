<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_voices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('sample_texts')->nullable();
            $table->json('generated_profile')->nullable();
            $table->string('language')->default('de');
            $table->text('no_go_words')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_voices');
    }
};
