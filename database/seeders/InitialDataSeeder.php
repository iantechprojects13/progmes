<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
    

        // DISCIPLINES
        DB::table('discipline')->insert([
            'discipline' => 'IT-Related Disciplines',
        ]);

        DB::table('discipline')->insert([
            'discipline' => 'Business Administration and Related',
        ]);

        DB::table('discipline')->insert([
            'discipline' => 'Education Science and Teacher Training',
        ]);

        // INSTITUTION
        DB::table('institution')->insert([
            'code' => '11093',
            'name' => 'University of Mindanao',
        ]);
        DB::table('institution')->insert([
            'code' => '11050',
            'name' => 'North Davao Colleges, Inc.',
        ]);
        DB::table('institution')->insert([
            'code' => '11035',
            'name' => 'Holy Cross of Davao College',
        ]);
        DB::table('institution')->insert([
            'code' => '11094',
            'name' => 'University of Southeastern Philippines, Obrero (Main)',
        ]);
		

        // PROGRAMS
        DB::table('program')->insert([
            'disciplineId' => 1,
            'program' => 'Bachelor of Science in Information Technology',
        ]);

        DB::table('program')->insert([
            'disciplineId' => 1,
            'program' => 'Bachelor of Science in Computer Science',
        ]);

        DB::table('program')->insert([
            'disciplineId' => 2,
            'program' => 'Bachelor of Science in Business Administration',
        ]);

        DB::table('program')->insert([
            'disciplineId' => 2,
            'program' => 'Bachelor of Science in Hospitality Management',
        ]);

        DB::table('program')->insert([
            'disciplineId' => 3,
            'program' => 'Bachelor of Elementary Education',
        ]);

        DB::table('program')->insert([
            'disciplineId' => 3,
            'program' => 'Bachelor of Secondary Education',
        ]);

        //institution_program
        // for($i = 1; $i <= 6; $i++) {
        //     DB::table('institution_program')->insert([
        //     'institutionId' => 1,
        //     'programId' => $i,
        //     ]);
        // }

        // for($i = 5; $i <= 6; $i++) {
        //     DB::table('institution_program')->insert([
        //     'institutionId' => 2,
        //     'programId' => $i,
        //     ]);
        // }

        // for($i = 1; $i <= 6; $i++) {
        //     DB::table('institution_program')->insert([
        //     'institutionId' => 3,
        //     'programId' => $i,
        //     ]);
        // }

        // for($i = 1; $i <= 6; $i++) {
        //     DB::table('institution_program')->insert([
        //     'institutionId' => 4,
        //     'programId' => $i,
        //     ]);
        // }

        DB::table('admin_settings')->insert([
            'currentAcademicYear' => '2024-2025',
        ]);
    }
}
