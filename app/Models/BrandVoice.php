<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BrandVoice extends Model
{
    protected $fillable = [
        'name',
        'sample_texts',
        'generated_profile',
        'language',
        'no_go_words',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sample_texts' => 'array',
            'generated_profile' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function drafts(): HasMany
    {
        return $this->hasMany(Draft::class);
    }
}
