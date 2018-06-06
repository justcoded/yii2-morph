<?php

use yii\db\Migration;

/**
 * Handles the creation of table `question`.
 */
class m180524_092250_create_question_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('question', [
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
		$this->dropTable('question');
	}
}
