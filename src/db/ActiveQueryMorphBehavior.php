<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.05.18
 * Time: 18:56
 */

namespace justcoded\yii2\filestorage\db;

use yii\base\Behavior;

class ActiveQueryMorphBehavior extends Behavior
{
	public $morphName;
	public $extraCondition;
}