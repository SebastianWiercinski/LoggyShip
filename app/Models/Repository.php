<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repository extends Model
{
    protected $fillable = [
        'github_id',
        'owner',
        'name',
        'full_name',
        'description',
        'default_branch',
        'is_active',
        'sync_commits',
        'sync_prs',
        'sync_releases',
        'last_synced_at',
        'sync_cursor',
    ];

    protected function casts(): array
    {
        return [
            'github_id' => 'integer',
            'is_active' => 'boolean',
            'sync_commits' => 'boolean',
            'sync_prs' => 'boolean',
            'sync_releases' => 'boolean',
            'last_synced_at' => 'datetime',
            'sync_cursor' => 'array',
        ];
    }

    public function sourceItems(): HasMany
    {
        return $this->hasMany(SourceItem::class);
    }

    public function drafts(): HasMany
    {
        return $this->hasMany(Draft::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
