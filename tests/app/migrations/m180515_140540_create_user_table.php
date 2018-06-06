<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post`.
 */
class m180515_140540_create_user_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('user', [
			'id'    => $this->primaryKey(),
			'name' => $this->string(),
			'email'  => $this->string(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('user');
	}
}
