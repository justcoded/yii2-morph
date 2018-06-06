<?php

namespace app\models;

use justcoded\yii2\morph\db\MorphRelationsTrait;

/**
 * Class Question
 *
 * @package justcoded\yii2\tests\app\models
 */
class Question extends \yii\db\ActiveRecord
{
	use MorphRelationsTrait;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'body'], 'required'],
			[['title', 'body'], 'string']
		];
	}

	/**
	 * Get all of the question's tags.
	 */
	public function getTags()
	{
		return $this->morphToMany(['taggable_id', 'taggable_type'], 'taggable', 'tag_id');
	}
}