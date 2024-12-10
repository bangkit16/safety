<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GambarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gambars')->insert([
            'gambar_id' => 1,
            'gambar' => 'gambar/logo.png',
            'patrol_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
