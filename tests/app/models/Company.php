<?php

namespace app\models;

use justcoded\yii2\morph\db\MorphRelationsTrait;

/**
 * Class Company
 *
 * @package justcoded\yii2\tests\app\models\
 */
class Company extends \yii\db\ActiveRecord
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
	 * Get all of the video's comments.
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getComments()
	{
		return $this->morphMany(Comment::class, 'commentable');
	}

	/**
	 * Get all tags.
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getTags()
	{
		return $this->morphMany(Tag::class, 'taggable', [], 'taggable', ['id' => 'tag_id']);
	}

	/**
	 * Get all billing addresses.
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getBillingAddresses()
	{
		return $this->morphMany(Address::class, 'addressable', ['type' => Address::TYPE_BILLING]);
	}

	/**
	 * Get all shipping addresses.
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getShippingAddresses()
	{
		return $this->morphMany(Address::class, 'addressable', ['type' => Address::TYPE_SHIPPING]);
	}

	/**
	 * Get company thumbnail.
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getThumbnail()
	{
		return $this->morphOne(Media::class, 'mediable', ['type' => 'thumbnail'], 'mediable', ['id' => 'media_id']);
	}

	/**
	 * Get all media from company gallery.
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getGallery()
	{
		return $this->morphMany(Media::class, 'mediable', ['type' => 'gallery'], 'mediable', ['id' => 'media_id']);
	}
}
