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
        Schema::create('play_list_music', function (Blueprint $table) {
            $table->id();
            $table->foreignId('play_list_id')->constrained('play_lists')->cascadeOnDelete();
            $table->foreignId('music_id')->constrained('musics')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('play_list_music');
    }
};
