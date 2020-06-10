<?php
$params = require __DIR__ . '/params/params.' . constant('YII_ENV') . '.php';

return [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [], //'log'
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'timeZone' => 'PRC',
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'enableCookieValidation' => false,
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            //'cookieValidationKey' => 'jPnsarEPLPY1jHBey090YJVIgdQbB4vG',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'enableSession' => false,
            'loginUrl' => null,
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'categories' => ['application'],
                    'logVars' => [],
                    'logFile' => '@runtime/logs/' . date('ymd') . 'app.log',
                ],
            ],
        ],
        'db' => $params['db'],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'GET <controller>s' => '<controller>/index',
                'GET <controller>s/<id:\d+>' => '<controller>/show',
                'POST <controller>s' => '<controller>/create',
                'PUT <controller>s/<id:\d+>' => '<controller>/update',
                'DELETE <controller>s/<id:\d+>' => '<controller>/delete',
                /*'GET tests' => 'test/index',
                'GET tests/<id:\d+>' => 'test/show',
                'POST tests' => 'test/create',
                'PUT tests/<id:\d+>' => 'test/update',
                'DELETE tests/<id:\d+>' => 'test/delete',*/
            ],
        ],
        'response' => [
            'format' => 'json',
            'on beforeSend' => function($event) {
                $response = $event->sender;
                if (!empty($response->data['status'])) {
                    $response->data = [
                        'code' => !empty($response->data['status']) ? $response->data['status'] : $response->data['code'],
                        'message' => !empty($response->data['message']) ? $response->data['message'] : '',
                        'data' => [],
                    ];
                }
                $response->data['runtime'] = bcsub(microtime(true), constant('YII_BEGIN_TIME'), 8);
            },
        ],
    ],
    'params' => $params['params'],
];

/*if (YII_ENV_DEV && YII_ENV_DEV != 'prod') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}*/
