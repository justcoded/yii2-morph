<?php

namespace app\models;

use justcoded\yii2\morph\db\MorphRelationsTrait;

/**
 * Class Taggable
 * @package justcoded\yii2\tests\app\models
 */
class Taggable extends \yii\db\ActiveRecord
{
    use MorphRelationsTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taggable_type', 'tag_id', 'taggable_id'], 'required'],
            [['tag_id', 'taggable_id'], 'string'],
            [['taggable_type'], 'string']
        ];
    }
}