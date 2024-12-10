<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatrolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('patrols')->insert([
            'patrol_id' => 1,
            'tanggal' => '2024-12-06',
            'divisi_id' => 1,
            'user_id' => 3,
            'temuan' => 'Ada Masalah di atap',
            'perbaikan' => 'Bangun ulang atap',
            'status' => 'Belum Dicek',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
