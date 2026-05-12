<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'slug',
    'summary',
    'description',
    'image_url',
    'client',
    'role',
    'year',
    'tech_stack',
    'live_url',
    'repo_url',
    'featured',
    'published',
    'display_order',
])]
class Project extends Model
{
    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
            'featured' => 'boolean',
            'published' => 'boolean',
            'year' => 'integer',
            'display_order' => 'integer',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
