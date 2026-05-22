<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'email',
    'source',
    'unsubscribe_token',
    'subscribed_at',
    'unsubscribed_at',
])]
class NewsletterSubscription extends Model
{
    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function isSubscribed(): bool
    {
        return $this->unsubscribed_at === null;
    }

    public function scopeSubscribed($query)
    {
        return $query->whereNull('unsubscribed_at');
    }

    public function ensureUnsubscribeToken(): void
    {
        if ($this->unsubscribe_token) {
            return;
        }

        $this->forceFill(['unsubscribe_token' => self::makeUnsubscribeToken()])->save();
    }

    public static function makeUnsubscribeToken(): string
    {
        do {
            $token = Str::random(48);
        } while (self::where('unsubscribe_token', $token)->exists());

        return $token;
    }
}
