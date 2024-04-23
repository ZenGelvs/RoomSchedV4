<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('programs')->insert([
            [
                'program_ID' => 'BSCS',
                'program_name' => 'Computer Science',
                'college' => 'COECSA',
                'department' => 'DCS',
                'years' => '4'
            ],
            [
                'program_ID' => 'BSIT',
                'program_name' => 'Information Technology',
                'college' => 'COECSA',
                'department' => 'DCS',
                'years' => '4'
            ],
            [
                'program_ID' => 'BSCE',
                'program_name' => 'Computer Engineering',
                'college' => 'COECSA',
                'department' => 'DCS',
                'years' => '5'
            ],
            [
                'program_ID' => 'BSLIS',
                'program_name' => 'Library and Information Science',
                'college' => 'COECSA',
                'department' => 'DCS',
                'years' => '4'
            ],
            [
                'program_ID' => 'BSCE',
                'program_name' => 'Civil Engineering',
                'college' => 'COECSA',
                'department' => 'DOE',
                'years' => '4'
            ],
            [
                'program_ID' => 'BSCE1',
                'program_name' => 'Program 1 DOE',
                'college' => 'COECSA',
                'department' => 'DOE',
                'years' => '4'
            ],
            [
                'program_ID' => 'BSCE2',
                'program_name' => 'Program 2 DOE',
                'college' => 'COECSA',
                'department' => 'DOE',
                'years' => '4'
            ],
            [
                'program_ID' => 'BST',
                'program_name' => 'Tourism',
                'college' => 'CITHM',
                'department' => 'Tourism',
                'years' => '4'
            ],
            [
                'program_ID' => 'BST1',
                'program_name' => 'CITHM COURSE 1 ',
                'college' => 'CITHM',
                'department' => 'Tourism',
                'years' => '4'
            ],
            [
                'program_ID' => 'BST2',
                'program_name' => 'CITHM COURSE 2 ',
                'college' => 'CITHM',
                'department' => 'Tourism',
                'years' => '4'
            ],
        ]);
    
    }
}
