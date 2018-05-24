<?php

namespace app\models;

use justcoded\yii2\morph\db\MorphRelationsTrait;

/**
 * Class Video
 * @package justcoded\yii2\tests\app\models\
 */
class Video extends \yii\db\ActiveRecord
{
    use MorphRelationsTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'required'],
            [['title', 'url'], 'string']
        ];
    }

    /**
     * Get all of the video's comments.
     */
    public function getComments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}