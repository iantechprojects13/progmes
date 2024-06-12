<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        DB::table('users')->insert([
            'id' => 2,
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'role' => 'Admin',
            'isVerified' => 1,
            'isActive' => 1,
            'type' => 'CHED',
            'avatar' => '/assets/user.png',
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'role' => 'Regional Director',
            'isVerified' => 1,
            'isActive' => 1,
            'type' => 'CHED',
            'avatar' => '/assets/user.png',
        ]);

        DB::table('users')->insert([
            'id' => 4,
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'role' => 'Chief Education Program Specialist',
            'isVerified' => 1,
            'isActive' => 1,
            'type' => 'CHED',
            'avatar' => '/assets/user.png',
        ]);

        DB::table('users')->insert([
            'id' => 5,
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'role' => 'Supervising Education Program Specialist',
            'isVerified' => 1,
            'isActive' => 1,
            'type' => 'CHED',
            'avatar' => '/assets/user.png',
        ]);


        for ($i = 0; $i < 30; $i++) {
            $name = $faker->name;
            $email = $faker->unique()->safeEmail;

            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->role = 'Education Supervisor';
            $user->isActive = 1;
            $user->isVerified = 1;
            $user->type = 'CHED';
            $user->avatar = '/assets/user.png';
            $user->save();
        }

        for ($i = 0; $i < 105; $i++) {
            $name = $faker->name;
            $email = $faker->unique()->safeEmail;

            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->role = 'Vice-President for Academic Affairs';
            $user->isActive = 1;
            $user->isVerified = 1;
            $user->type = 'HEI';
            $user->avatar = '/assets/user.png';
            $user->save();
        }

        for ($i = 0; $i < 442; $i++) {
            $name = $faker->name;
            $email = $faker->unique()->safeEmail;

            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->role = 'Dean';
            $user->isActive = 1;
            $user->isVerified = 1;
            $user->type = 'HEI';
            $user->avatar = '/assets/user.png';
            $user->save();
        }

        for ($i = 0; $i < 1563; $i++) {
            $name = $faker->name;
            $email = $faker->unique()->safeEmail;

            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->role = 'Program Head';
            $user->isActive = 1;
            $user->isVerified = 1;
            $user->type = 'HEI';
            $user->avatar = '/assets/user.png';
            $user->save();
        }

    }
}
