<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Draft extends Model
{
    protected $fillable = [
        'repository_id',
        'title',
        'teaser',
        'body_markdown',
        'body_html',
        'category',
        'status',
        'confidence_score',
        'source_bundle',
        'brand_voice_id',
    ];

    protected function casts(): array
    {
        return [
            'confidence_score' => 'float',
            'source_bundle' => 'array',
        ];
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    public function brandVoice(): BelongsTo
    {
        return $this->belongsTo(BrandVoice::class);
    }

    public function sourceItems(): BelongsToMany
    {
        return $this->belongsToMany(SourceItem::class);
    }

    public function post(): HasOne
    {
        return $this->hasOne(Post::class);
    }
}
