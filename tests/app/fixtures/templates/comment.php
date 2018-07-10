<?php

use app\models\Company;
use app\models\User;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$commentable_type = $faker->randomElement([User::class, Company::class]);

return [
    'id' => $index + 1,
    'body' => $faker->text,
    'commentable_id' => $faker->randomElement($commentable_type::find()->select('id')->column()),
    'commentable_type' => $commentable_type,
];
