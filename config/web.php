<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id'         => 'basic',
    'name'       => 'Selfpub',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'extensions' => require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
    'components' => [
        'cache'        => [
            'class'     => \yii\redis\Cache::className(),
            'keyPrefix' => 'selfpub'
        ],
        'redis'        => [
            'class'    => \yii\redis\Connection::className(),
            'hostname' => 'localhost',
            'port'     => 6379,
            'database' => 5,
        ],
        'user'         => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mail'         => [
            'class'            => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => \yii\log\FileTarget::className(),
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'           => $db,
    ],
    'params'     => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
