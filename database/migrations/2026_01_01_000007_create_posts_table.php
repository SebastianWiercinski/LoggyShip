<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draft_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('repository_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('teaser')->nullable();
            $table->text('body_markdown');
            $table->text('body_html');
            $table->string('category')->default('improved');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();

            $table->index('is_published');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
