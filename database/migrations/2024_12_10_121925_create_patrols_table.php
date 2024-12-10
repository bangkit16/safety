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
        Schema::create('patrols', function (Blueprint $table) {
            $table->id('patrol_id');
            $table->date('tanggal');
            $table->unsignedBigInteger('divisi_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->text('temuan')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->enum('status', ['Setuju Semua', 'Setuju Admin', 'Belum Dicek']);
            $table->timestamps();

            $table->foreign('divisi_id')->references('divisi_id')->on('divisis');
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrols');
    }
};
