<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'slug',
    'excerpt',
    'body',
    'cover_image_url',
    'featured',
    'published',
    'published_at',
    'newsletter_sent_at',
    'display_order',
])]
class BlogPost extends Model
{
    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'published' => 'boolean',
            'published_at' => 'datetime',
            'newsletter_sent_at' => 'datetime',
            'display_order' => 'integer',
        ];
    }

    public function scopePublished($query)
    {
        return $query
            ->where('published', true)
            ->where(function ($query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function isVisibleOnPortfolio(): bool
    {
        return $this->published
            && (! $this->published_at || $this->published_at->lte(now()));
    }
}
