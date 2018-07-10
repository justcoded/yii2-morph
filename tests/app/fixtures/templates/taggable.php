<?php

use app\models\Company;
use app\models\Tag;
use app\models\User;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$taggable_type = $faker->randomElement([User::class, Company::class]);

return [
    'tag_id' => $faker->randomElement(Tag::find()->select('id')->column()),
    'taggable_id' => $faker->randomElement($taggable_type::find()->select('id')->column()),
    'taggable_type' => $taggable_type,
];
