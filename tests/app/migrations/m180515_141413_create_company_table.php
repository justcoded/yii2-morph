<?php

use yii\db\Migration;

/**
 * Handles the creation of table `video`.
 */
class m180515_141413_create_company_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('company', [
			'id'    => $this->primaryKey(),
			'name' => $this->string(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('company');
	}
}
