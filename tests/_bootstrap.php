<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/app/bootstrap.php';
// support .env file
load_dotenv(__DIR__ . '/../.env');