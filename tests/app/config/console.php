<?php
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/base.php'),
    [
        'controllerNamespace' => 'app\commands',
    ]
);