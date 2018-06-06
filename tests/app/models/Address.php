<?php

namespace app\models;

use justcoded\yii2\morph\db\MorphRelationsTrait;

/**
 * Class Address
 *
 * @package justcoded\yii2\tests\app\models
 */
class Address extends \yii\db\ActiveRecord
{
	use MorphRelationsTrait;

	const TYPE_BILLING = 'billing';
	const TYPE_SHIPPING = 'shipping';

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['addressable_id', 'addressable_type', 'type'], 'required'],
			[['addressable_type', 'type', 'city', 'street'], 'string'],
			[['addressable_id'], 'integer'],
		];
	}
	
	/**
	 * Get queries to get all related address owners, queries are indexed by address type.
	 *
	 * @return \yii\db\ActiveQuery[]
	 */
	public function getAddressable()
	{
		return $this->morphToMany('addressable', null, null);
	}
	
}
