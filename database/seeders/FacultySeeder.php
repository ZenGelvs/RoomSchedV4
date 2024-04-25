<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faculty')->insert([
            [
                'faculty_id' => '2020201524',
                'name' => 'Bruce Wayne',
                'college' => 'COECSA',
                'department' => 'DCS',
            ],
            [
                'faculty_id' => '2020201234',
                'name' => 'Tony Stark',
                'college' => 'COECSA',
                'department' => 'DCS',
            ],
            [
                'faculty_id' => '1000000001',
                'name' => 'Reed Richards',
                'college' => 'COECSA',
                'department' => 'DCS',
            ],
            [
                'faculty_id' => '2017102635',
                'name' => 'Barry Allen',
                'college' => 'COECSA',
                'department' => 'DOE',
            ],
            [
                'faculty_id' => '2',
                'name' => 'Clark Kent',
                'college' => 'COECSA',
                'department' => 'DOE',
            ],
            [
                'faculty_id' => '1',
                'name' => 'John Stewart',
                'college' => 'COECSA',
                'department' => 'DOE',
            ],
        ]);
    }
}
