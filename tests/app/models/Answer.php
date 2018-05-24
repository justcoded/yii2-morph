<?php

namespace app\models;

use justcoded\yii2\morph\db\MorphRelationsTrait;

/**
 * Class Answer
 * @package justcoded\yii2\tests\app\models
 */
class Answer extends \yii\db\ActiveRecord
{
    use MorphRelationsTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body'], 'required'],
            [['title', 'body'], 'string']
        ];
    }

    /**
     * Get all of the answer's tags.
     */
    public function getTags()
    {
        return $this->morphToMany(['taggable_id', 'taggable_type'], 'taggable', 'tag_id');
    }
}