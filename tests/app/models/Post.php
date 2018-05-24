<?php

namespace app\models;

use justcoded\yii2\morph\db\MorphRelationsTrait;

/**
 * Class Post
 * @package justcoded\yii2\tests\app\models
 */
class Post extends \yii\db\ActiveRecord
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
     * Get all of the post's comments.
     */
    public function getComments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}