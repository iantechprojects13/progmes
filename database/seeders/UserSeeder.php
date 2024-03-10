<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $name = $faker->name;
            $email = strtolower(str_replace(' ', '.', $name)) . '@gmail.com';
            $password = Hash::make('randompassword');
            $type = $faker->randomElement(['HEI', 'CHED']);
            
            DB::table('users')->insert([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'type' => $type,
            ]);
        }
        
    }
}
