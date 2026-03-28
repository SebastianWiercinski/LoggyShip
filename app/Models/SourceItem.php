<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SourceItem extends Model
{
    protected $fillable = [
        'repository_id',
        'type',
        'github_id',
        'title',
        'body',
        'author',
        'url',
        'metadata',
        'relevance_score',
        'is_user_facing',
        'classification_reason',
        'is_excluded',
        'created_on_github_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'relevance_score' => 'float',
            'is_user_facing' => 'boolean',
            'is_excluded' => 'boolean',
            'created_on_github_at' => 'datetime',
        ];
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    public function drafts(): BelongsToMany
    {
        return $this->belongsToMany(Draft::class);
    }
}
