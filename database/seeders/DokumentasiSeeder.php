<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DokumentasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dokumentasis')->insert([
            'dokumentasi_id' => 1,
            'dokumentasi' => 'dokumentasi/logo.png',
            'perbaikan_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
