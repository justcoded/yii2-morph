<?php

use app\models\Post;
use app\models\Video;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$commentable_type = $faker->randomElement([Post::class, Video::class]);

return [
	'body'             => $faker->text,
	'commentable_id'   => $faker->randomElement($commentable_type::find()->select('id')->column()),
	'commentable_type' => $commentable_type,
];