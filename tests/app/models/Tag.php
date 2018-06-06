<?php

namespace app\models;

use justcoded\yii2\morph\db\MorphRelationsTrait;

/**
 * Class Tag
 *
 * @package justcoded\yii2\tests\app\models
 */
class Tag extends \yii\db\ActiveRecord
{
	use MorphRelationsTrait;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['name'], 'string']
		];
	}

	/**
	 * Get all of the answers that are assigned this tag.
	 */
	public function getAnswers()
	{
		return $this->morphMany(Answer::class, 'taggable');
	}

	/**
	 * Get all of the questions that are assigned this tag.
	 */
	public function getQuestions()
	{
		return $this->morphMany(Question::class, ['taggable_id', 'taggable_type'], [], 'taggable', 'tag_id');
	}
}