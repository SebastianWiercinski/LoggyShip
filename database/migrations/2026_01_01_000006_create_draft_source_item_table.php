<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('draft_source_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draft_id')->constrained()->cascadeOnDelete();
            $table->foreignId('source_item_id')->constrained()->cascadeOnDelete();

            $table->unique(['draft_id', 'source_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('draft_source_item');
    }
};
