<?php

namespace justcoded\yii2\morph\db;

use yii\base\Behavior;

/**
 * Class ActiveQueryMorphBehavior
 * @package justcoded\yii2\morph\db
 */
class ActiveQueryMorphBehavior extends Behavior
{
    public $morphName;
    public $extraCondition;
}