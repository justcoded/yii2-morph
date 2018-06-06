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
	 *
	 * @return \yii\db\ActiveQuery[]
	 */
	public function getTaggable()
	{
		return $this->morphToMany('taggable', 'taggable', 'tag_id');
	}

}
