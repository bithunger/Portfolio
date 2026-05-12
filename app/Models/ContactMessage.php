<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'email',
    'company',
    'subject',
    'message',
    'read_at',
])]
class ContactMessage extends Model
{
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function getIsUnreadAttribute(): bool
    {
        return $this->read_at === null;
    }
}
