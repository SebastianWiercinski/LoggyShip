<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'draft_id',
        'repository_id',
        'slug',
        'title',
        'teaser',
        'body_markdown',
        'body_html',
        'category',
        'is_release_note',
        'version',
        'is_published',
        'published_at',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_release_note' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function draft(): BelongsTo
    {
        return $this->belongsTo(Draft::class);
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
