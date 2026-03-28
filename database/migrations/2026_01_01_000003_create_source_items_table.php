<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('source_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // commit, pull_request, release
            $table->string('github_id');
            $table->text('title');
            $table->text('body')->nullable();
            $table->string('author')->nullable();
            $table->string('url')->nullable();
            $table->json('metadata')->nullable();
            $table->float('relevance_score')->nullable();
            $table->boolean('is_user_facing')->nullable();
            $table->text('classification_reason')->nullable();
            $table->boolean('is_excluded')->default(false);
            $table->timestamp('created_on_github_at');
            $table->timestamps();

            $table->unique(['repository_id', 'type', 'github_id']);
            $table->index('is_user_facing');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('source_items');
    }
};
