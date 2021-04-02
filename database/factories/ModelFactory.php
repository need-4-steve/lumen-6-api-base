<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

function randDate()
{
    return \Carbon\Carbon::now()
        ->subDays(rand(1, 100))
        ->subHours(rand(1, 23))
        ->subMinutes(rand(1, 60));
}

$factory->define(User::class, function (Faker $faker) {
    static $createdAt;
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => $password ?: $password = app('hash')->make(123456),
        'created_at' => $createdAt ?: $createAt = randDate(),
        'updated_at' => $createdAt ?: $createdAt = randDate(),
    ];
});

$factory->define(App\Post::class, function (Faker $faker) {
    static $createdAt;
    static $userIds;
    $userIds = $userIds ?: User::pluck('id')->toArray();

    return [
        'user_id' => $faker->randomElement($userIds),
        'title' => $faker->sentence(),
        'content' => $faker->text(),
        'created_at' => $createdAt ?: $createAt = randDate(),
        'updated_at' => $createdAt ?: $createdAt = randDate(),
    ];
});

$factory->define(App\Comment::class, function (Faker $faker) {
    static $createdAt;
    static $userIds;
    static $postIds;

    $userIds = $userIds ?: User::pluck('id')->toArray();
    $postIds = $postIds ?: App\Post::pluck('id')->toArray();

    return [
        'user_id' => $faker->randomElement($userIds),
        'post_id' => $faker->randomElement($postIds),
        'content' => $faker->text,
        'created_at' => $createdAt ?: $createAt = randDate(),
        'updated_at' => $createdAt ?: $createdAt = randDate(),
    ];
});
