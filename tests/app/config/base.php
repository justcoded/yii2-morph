<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'runtimePath' => dirname(dirname(__DIR__)) . '/_output',
    'bootstrap' => ['log'],
    'aliases' => [
        '@migrations' => dirname(__DIR__) . '/migrations',
        '@fixtures' => dirname(__DIR__) . '/fixtures',
        '@app/fixtures' => '@fixtures',
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationPath' => [
                '@migrations',
            ],
        ],
        'fixture' => [
            'class' => \yii\faker\FixtureController::class,
            'namespace' => 'app\fixtures',
            'templatePath' => '@fixtures/templates',
            'fixtureDataPath' => '@fixtures/data',
        ],
    ],
    'components' => [
        'log' => [
            'flushInterval' => 1,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning', 'trace'],
                    'exportInterval' => 1,
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];

return $config;
