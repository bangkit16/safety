<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_id' => 1,
            'name' => 'Admin',
            'email' => 'admin@white.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'user_id' => 2,
            'name' => 'Management',
            'email' => 'management@white.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'user_id' => 3,
            'name' => 'Inspektor',
            'email' => 'inspektor@white.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'role_id' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'user_id' => 4,
            'name' => 'User',
            'email' => 'user@white.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'role_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
