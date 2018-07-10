<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mediable`.
 */
class m180606_170036_create_mediable_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('mediable', [
            'media_id' => $this->integer(),
            'mediable_id' => $this->integer(),
            'mediable_type' => $this->string(),
            'attribute' => $this->string(),
        ]);

        $this->addForeignKey('fk_media_id', 'mediable', 'media_id', 'media', 'id', 'CASCADE');
        $this->createIndex('idx_mediable_id', 'mediable', 'mediable_id');
        $this->createIndex('idx_mediable_type', 'mediable', 'mediable_type');
        $this->createIndex('idx_attribute', 'mediable', 'type');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('mediable');
    }
}
