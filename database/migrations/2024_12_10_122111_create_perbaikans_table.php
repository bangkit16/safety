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
        Schema::create('perbaikans', function (Blueprint $table) {
            $table->id('perbaikan_id');
            $table->text('perbaikan');
            $table->date('target');
            $table->unsignedBigInteger('patrol_id')->index();
            $table->unsignedBigInteger('divisi_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->enum('status', ['Selesai', 'Proses']);
            $table->string('dokumentasi');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('divisi_id')->references('divisi_id')->on('divisis');
            $table->foreign('patrol_id')->references('patrol_id')->on('patrols');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikans');
    }
};
