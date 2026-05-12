<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'email',
    'source',
    'subscribed_at',
])]
class NewsletterSubscription extends Model
{
    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
        ];
    }
}
