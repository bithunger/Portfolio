<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'title',
    'company',
    'quote',
    'avatar_url',
    'featured',
    'active',
    'display_order',
])]
class Testimonial extends Model
{
    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
