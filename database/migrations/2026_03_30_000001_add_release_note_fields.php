<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drafts', function (Blueprint $table) {
            $table->boolean('is_release_note')->default(false)->after('category');
            $table->string('version')->nullable()->after('is_release_note');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_release_note')->default(false)->after('category');
            $table->string('version')->nullable()->after('is_release_note');
        });
    }

    public function down(): void
    {
        Schema::table('drafts', function (Blueprint $table) {
            $table->dropColumn(['is_release_note', 'version']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['is_release_note', 'version']);
        });
    }
};
