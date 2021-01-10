<?php
// для локального развёртывания необходимо переименовать в local_settings.php и установить свои параметры базы данных
$config = include (__DIR__) . '/settings.php';
$config['components']['db'] = [
    'class' => \Phact\Orm\ConnectionManager::class,
    'properties' => [
        'connections' => [
            'default' => [
                'host' => '127.0.0.1',
                'dbname' => 'same_base',
                'user' => 'same_user',
                'password' => 'password',
                'charset' => 'utf8',
                'driver' => 'pdo_mysql',
            ]
        ]
    ],
];

return $config;