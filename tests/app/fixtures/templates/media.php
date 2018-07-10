<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'id' => $index + 1,
    'type' => $faker->randomElement(['image', 'video', 'file']),
    'file' => 'tmp/' . $faker->md5 . '.' . $faker->fileExtension,
    'thumb' => $faker->imageUrl($faker->numberBetween(600, 1200), $faker->numberBetween(400, 800)),
];