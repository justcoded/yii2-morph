<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180606_170043_create_address_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('address', [
			'id'               => $this->primaryKey(),
			'addressable_id'   => $this->integer(),
			'addressable_type' => $this->string(),
			'type'             => $this->string(),
			'city'             => $this->string(),
			'street'           => $this->string(),
		]);

		$this->createIndex('idx_addressable_id', 'address', 'addressable_id');
		$this->createIndex('idx_addressable_type', 'address', 'addressable_type');
		$this->createIndex('idx_type', 'address', 'type');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('address');
	}
}
