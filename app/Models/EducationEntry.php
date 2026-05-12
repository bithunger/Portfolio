<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'degree',
    'institution',
    'location',
    'start_year',
    'end_year',
    'summary',
    'highlights',
    'display_order',
    'active',
])]
class EducationEntry extends Model
{
    protected function casts(): array
    {
        return [
            'start_year' => 'integer',
            'end_year' => 'integer',
            'highlights' => 'array',
            'display_order' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
