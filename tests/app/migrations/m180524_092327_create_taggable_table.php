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
            'taggable_type' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('taggable');
    }
}
