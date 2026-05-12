<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'icon',
    'description',
    'deliverables',
    'display_order',
    'active',
])]
class Service extends Model
{
    protected function casts(): array
    {
        return [
            'deliverables' => 'array',
            'display_order' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
