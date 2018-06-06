<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m180515_141757_create_comment_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('comment', [
			'id'               => $this->primaryKey(),
			'body'             => $this->text(),
			'commentable_id'   => $this->integer(),
			'commentable_type' => $this->string()
		]);

		$this->createIndex('idx_commentable_id', 'comment', 'commentable_id');
		$this->createIndex('idx_commentable_type', 'comment', 'commentable_type');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('comment');
	}
}
