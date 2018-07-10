<?php

use yii\db\Migration;

/**
 * Handles the creation of table `taggable`.
 */
class m180524_092327_create_taggable_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('taggable', [
            'tag_id' => $this->integer(),
            'taggable_id' => $this->integer(),
            'taggable_type' => $this->string(),
        ]);

        $this->addForeignKey('fk_tag_id', 'taggable', 'tag_id', 'tag', 'id', 'CASCADE');
        $this->createIndex('idx_taggable_id', 'taggable', 'taggable_id');
        $this->createIndex('idx_taggable_type', 'taggable', 'taggable_type');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('taggable');
    }
}
