<?php

namespace app\models;

use justcoded\yii2\morph\db\MorphRelationsTrait;

/**
 * Class Media
 *
 * @package justcoded\yii2\tests\app\models
 */
class Media extends \yii\db\ActiveRecord
{
    use MorphRelationsTrait;

    const TYPE_THUMBNAIL = 'thumbnail';
    const TYPE_GALLERY = 'gallery';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'file', 'thumb'], 'required'],
            [['type', 'file', 'thumb'], 'string'],
            [['type'], 'in', 'range' => ['image', 'video', 'file']],
        ];
    }


}