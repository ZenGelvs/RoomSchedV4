<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'DCS',
                'email' => 'Gelvin@Gelvin',
                'password' => Hash::make('DCS'),
                'remember_token' => Str::random(10),
                'college' => 'COECSA',
                'department' => 'DCS',
            ],
            [
                'name' => 'DOA',
                'email' => 'Adrian@Adrian',
                'password' => Hash::make('DOA'),
                'remember_token' => Str::random(10),
                'college' => 'COECSA',
                'department' => 'DOE',
            ],
            [
                'name' => 'CITHM',
                'email' => 'Melvin@Melvin',
                'password' => Hash::make('CITHM'),
                'remember_token' => Str::random(10),
                'college' => 'CITHM',
                'department' => 'TOURISM',
            ],

            [
                'name' => 'Admin',
                'email' => 'Admin@admin',
                'password' => Hash::make('Admin'),
                'remember_token' => Str::random(10),
                'college' => 'ADMIN',
                'department' => 'ADMIN',
            ],
            [
                'name' => 'Room Coordinator',
                'email' => 'ROOM@room',
                'password' => Hash::make('ROOM'),
                'remember_token' => Str::random(10),
                'college' => 'ROOM COORDINATOR',
                'department' => 'ROOM COORDINATOR',
            ],
        ]);
    }
}
