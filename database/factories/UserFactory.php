<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);
    if($gender === 'male'){
        $firstName = $faker->firstNameMale;
    }else{
        $firstName = $faker->firstNameFemale;
    }
    return [
        'first_name' => $firstName,
        'last_name' => $faker->lastName,
        'gender' => substr($gender, 0, 1),
        'country' => $faker->stateAbbr,
        'bonus' => rand(5, 20),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
