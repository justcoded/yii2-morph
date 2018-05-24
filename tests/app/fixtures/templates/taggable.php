<?php
use app\models\Question;
use app\models\Answer;
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$taggable_type = $faker->randomElement([Question::class, Answer::class]);

return [
    'tag_id' => $faker->randomElement($taggable_type::find()->select('id')->column()),
    'taggable_id' => $faker->randomElement($taggable_type::find()->select('id')->column()),
    'taggable_type' => $taggable_type,
];