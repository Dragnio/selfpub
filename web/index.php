<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
defined('ROOT_DIR') or define('ROOT_DIR', dirname(__FILE__));

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
ini_set('display_errors', 'on');
error_reporting(E_ALL);
(new yii\web\Application($config))->run();
