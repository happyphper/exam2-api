<?php

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

$factory->define(App\Models\Question::class, function (Faker $faker) {
    // 生成 options
    $options = [];
    $answers = [0,0,0,1];
    shuffle($answers);
    for ($i = 1; $i <= 4; $i++) {
        $options[] = [
            'id' => $i,
            'content' => $faker->paragraph(1),
            'right' => array_pop($answers)
        ];
    }
    return [
        'title' => $faker->name,
        'options' => json_encode($answers)
    ];
});
