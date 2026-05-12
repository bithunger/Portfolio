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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('client')->nullable();
            $table->string('role')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->json('tech_stack')->nullable();
            $table->string('live_url')->nullable();
            $table->string('repo_url')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
