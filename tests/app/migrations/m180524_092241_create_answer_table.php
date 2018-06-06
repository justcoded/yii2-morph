<?php

use yii\db\Migration;

/**
 * Handles the creation of table `answer`.
 */
class m180524_092241_create_answer_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('answer', [
			'id'    => $this->primaryKey(),
			'title' => $this->string(),
			'body'  => $this->text()
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('answer');
	}
}
