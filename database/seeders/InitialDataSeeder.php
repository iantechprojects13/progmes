<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   

        DB::table('users')->insert([
            'name' => '1Super Admin',
            'email' => 'ianalbano@gmail.com',
            'role' => 'Super Admin',
            'isVerified' => 1,
            'isActive' => 1,
            'type' => 'CHED',
            'avatar' => '/assets/user.png',
        ]);

        
        DB::table('admin_settings')->insert([
            'currentAcademicYear' => '2023-2024',
        ]);

    }
}
