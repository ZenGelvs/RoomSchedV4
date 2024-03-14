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
                'name' => 'Gelvin',
                'email' => 'Gelvin@Gelvin',
                'password' => Hash::make('Gelvin'),
                'remember_token' => Str::random(10),
                'college' => 'COECSA',
                'department' => 'DCS',
            ],
            [
                'name' => 'Adrian',
                'email' => 'Adrian@Adrian',
                'password' => Hash::make('Adrian'),
                'remember_token' => Str::random(10),
                'college' => 'COECSA',
                'department' => 'DOE',
            ],
            [
                'name' => 'Melvin',
                'email' => 'Melvin@Melvin',
                'password' => Hash::make('Melvin'),
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
        ]);
    }
}
