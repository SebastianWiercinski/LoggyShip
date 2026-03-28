<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('github_id')->unique();
            $table->string('owner');
            $table->string('name');
            $table->string('full_name');
            $table->text('description')->nullable();
            $table->string('default_branch')->default('main');
            $table->boolean('is_active')->default(true);
            $table->boolean('sync_commits')->default(true);
            $table->boolean('sync_prs')->default(true);
            $table->boolean('sync_releases')->default(true);
            $table->timestamp('last_synced_at')->nullable();
            $table->json('sync_cursor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
};
