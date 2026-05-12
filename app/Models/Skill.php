<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'category',
    'proficiency',
    'display_order',
    'active',
])]
class Skill extends Model
{
    protected function casts(): array
    {
        return [
            'proficiency' => 'integer',
            'display_order' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
