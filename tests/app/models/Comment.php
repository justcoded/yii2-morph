<?php

namespace app\models;

use justcoded\yii2\morph\db\MorphRelationsTrait;

/**
 * Class Comment
 *
 * @package justcoded\yii2\tests\app\models
 */
class Comment extends \yii\db\ActiveRecord
{
	use MorphRelationsTrait;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['commentable_id', 'commentable_type', 'body'], 'required'],
			[['body', 'commentable_type'], 'string'],
			[['commentable_id'], 'integer'],
		];
	}

	/**
	 * Get all of the owning commentable post models.
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCommentable()
	{
		return $this->morphToOne('commentable');
	}
}
