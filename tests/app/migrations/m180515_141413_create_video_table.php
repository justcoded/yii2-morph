<?php

use yii\db\Migration;

/**
 * Handles the creation of table `video`.
 */
class m180515_141413_create_video_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('video', [
			'id'    => $this->primaryKey(),
			'title' => $this->string(),
			'url'   => $this->string()
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('video');
	}
}
