<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerbaikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('perbaikans')->insert([
            'perbaikan_id' => 1,
            'perbaikan' => 'perlu diganti',
            'target' => '2024-12-16',
            'divisi_id' => 1,
            'patrol_id' => 1,
            'user_id' => 3,
            'dokumentasi' => 'dokumentasi.jpg',
            'status' => 'Selesai',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
