<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\Company;
use app\models\User;

$addressable_type = $faker->randomElement([User::class, Company::class]);

return [
    'id' => $index + 1,
    'addressable_id' => $faker->randomElement($addressable_type::find()->select('id')->column()),
    'addressable_type' => $addressable_type,
    'type' => $faker->randomElement(['billing', 'shipping']),
    'city' => $faker->city,
    'street' => $faker->streetAddress,
];