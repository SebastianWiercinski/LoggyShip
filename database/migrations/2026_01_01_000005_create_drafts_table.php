<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('teaser')->nullable();
            $table->text('body_markdown');
            $table->text('body_html')->nullable();
            $table->string('category')->default('improved'); // new, improved, fixed, performance, security
            $table->string('status')->default('draft'); // draft, review, published, discarded
            $table->float('confidence_score')->nullable();
            $table->json('source_bundle')->nullable();
            $table->foreignId('brand_voice_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drafts');
    }
};
