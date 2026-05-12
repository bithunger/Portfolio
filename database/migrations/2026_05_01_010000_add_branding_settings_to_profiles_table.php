<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('frontend_background_color', 20)->default('#f7f8f6')->after('accent_color');
            $table->string('backend_background_color', 20)->default('#f7f8f6')->after('frontend_background_color');
            $table->string('font_family', 40)->default('elegant')->after('backend_background_color');
            $table->string('frontend_logo_url')->nullable()->after('font_family');
            $table->string('backend_logo_url')->nullable()->after('frontend_logo_url');
            $table->string('favicon_url')->nullable()->after('backend_logo_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'frontend_background_color',
                'backend_background_color',
                'font_family',
                'frontend_logo_url',
                'backend_logo_url',
                'favicon_url',
            ]);
        });
    }
};
