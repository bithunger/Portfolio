<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('hero_panel_label')->nullable()->after('tagline');
            $table->string('hero_panel_text')->nullable()->after('hero_panel_label');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['hero_panel_label', 'hero_panel_text']);
        });
    }
};
