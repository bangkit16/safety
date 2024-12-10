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
        Schema::create('dokumentasis', function (Blueprint $table) {
            $table->id('dokumentasi_id');
            $table->string('dokumentasi');
            $table->unsignedBigInteger('perbaikan_id')->index();
            $table->timestamps();

            $table->foreign('perbaikan_id')->references('perbaikan_id')->on('perbaikans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasis');
    }
};
