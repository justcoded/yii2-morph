<?php

use yii\db\Migration;

/**
 * Handles the creation of table `media`.
 */
class m180606_170029_create_media_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('media', [
            'id' => $this->primaryKey(),
            'type' => $this->string(),
            'file' => $this->string(),
            'thumb' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('media');
    }
}
