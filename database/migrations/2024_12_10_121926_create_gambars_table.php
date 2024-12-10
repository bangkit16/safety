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
        Schema::create('gambars', function (Blueprint $table) {
            $table->id('gambar_id');
            $table->string('gambar');
            $table->unsignedBigInteger('patrol_id')->index();
            $table->timestamps();

            $table->foreign('patrol_id')->references('patrol_id')->on('patrols');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambars');
    }
};
