<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\text;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perbaikans', function (Blueprint $table) {
            $table->id('perbaikan_id');
            $table->unsignedBigInteger('patrol_id')->index();
            $table->string('temuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->text('perbaikan')->nullable();
            $table->date('target')->nullable();
            $table->enum('status', ['Setuju Semua', 'Setuju Admin', 'Belum Dicek', 'Proses', 'Selesai', 'Lolos Admin', 'Lolos Semua', 'Ditolak Admin', 'Ditolak Management','Batal Admin', 'Batal Management']);
            $table->string('dokumentasi')->nullable();
            $table->text('revisi')->nullable();
            $table->timestamps();

            $table->foreign('patrol_id')->references('patrol_id')->on('patrols');
            $table->foreign('user_id')->references('user_id')->on('users');
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
