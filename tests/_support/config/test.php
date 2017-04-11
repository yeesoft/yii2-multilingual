<?php

return [
    'id' => 'multilingual-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'controllerNamespace' => 'controllers',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=multilingual',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => dirname(__DIR__) . '/../../vendor/bower-asset/jquery/dist',
                ],
            ]
        ],
        'urlManager' => [
            'class' => yeesoft\multilingual\web\MultilingualUrlManager::className(),
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => [
                '<action:(login)>' => 'site/<action>',
                '<language:([a-zA-Z-]{2,5})?>' => 'site/index',
                '<language:([a-zA-Z-]{2,5})?>/<action:[\w \-]+>' => 'site/<action>',
                '<language:([a-zA-Z-]{2,5})?>/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
            'excludedActions' => [
                'site/login',
            ],
            'languages' => [
                'en-US' => 'English',
                'es' => 'Español',
                'pt-BR' => 'Português',
            ],
            'languageRedirects' => [
                'pt-BR' => 'pt',
            ]
        ],
        'user' => [
            'identityClass' => 'app\models\User',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],
    'params' => require(__DIR__ . '/params.php'),
];
