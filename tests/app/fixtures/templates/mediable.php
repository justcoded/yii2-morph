<?php

use app\models\Company;
use app\models\Media;
use app\models\User;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$mediable_type = $faker->randomElement([User::class, Company::class]);

return [
    'media_id' => $faker->randomElement(Media::find()->select('id')->column()),
    'attribute' => $faker->randomElement(['thumbnail', 'gallery']),
    'mediable_id' => $faker->randomElement($mediable_type::find()->select('id')->column()),
    'mediable_type' => $mediable_type,
];
