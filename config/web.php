<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id'         => 'basic',
    'name'       => 'SelfPub Inc.',
    'language'   => 'en_US',
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
            'class' => 'amnah\yii2\user\components\User',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mail'         => [
            'class'            => \yii\swiftmailer\Mailer::className(),
            'useFileTransport' => false,
            'messageConfig'    => [
                'from'    => ['admin@selfpub.bwhost.ru' => 'SelfPub'], // this is needed for sending emails
                'charset' => 'UTF-8',
            ]
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
        'urlManager'   => [
            'showScriptName'  => false,
            'enablePrettyUrl' => true,
            'suffix'          => '.html',
            'rules'           => [
                //default
                [
                    'pattern' => 'requests/<requestId:\d+>',
                    'route'   => 'requests/view'
                ],
                [
                    'pattern' => '<controller:\w+>/<action:\w+>',
                    'route'   => '<controller>/<action>'
                ],
                [
                    'pattern' => '<module:\w+>/<controller:\w+>',
                    'route'   => '<module>/<controller>/index'
                ],
                [
                    'pattern' => '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>',
                    'route'   => '<module>/<controller>/<action>'
                ],
            ]
        ],
    ],
    'modules'    => [
        'user' => [
            'class' => 'amnah\yii2\user\Module',
            // set custom module properties here ...
        ],
    ],
    'params'     => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'allowedIPs' => ['192.168.56.1'],
    ];
}

return $config;
