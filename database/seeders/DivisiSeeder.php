<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('divisis')->insert([
            'divisi_id' => 1,
            'nama' => 'Keuangan',
            'tanda_tangan' => 'divisi/tanda_tanga1.png',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('divisis')->insert([
            'divisi_id' => 2,
            'nama' => 'Manager',
            'tanda_tangan' => 'divisi/tanda_tangan2.png',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
