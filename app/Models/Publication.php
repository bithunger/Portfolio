<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'year',
    'journal_name',
    'publisher',
    'article_url',
    'icon',
    'display_order',
    'active',
])]
class Publication extends Model
{
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'display_order' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
