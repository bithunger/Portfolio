<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('newsletter_subscriptions', function (Blueprint $table) {
            $table->string('unsubscribe_token', 64)->nullable()->unique();
            $table->timestamp('unsubscribed_at')->nullable();
        });

        DB::table('newsletter_subscriptions')
            ->whereNull('unsubscribe_token')
            ->orderBy('id')
            ->each(function (object $subscription): void {
                DB::table('newsletter_subscriptions')
                    ->where('id', $subscription->id)
                    ->update(['unsubscribe_token' => Str::random(48)]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsletter_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['unsubscribe_token', 'unsubscribed_at']);
        });
    }
};
