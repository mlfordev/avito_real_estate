<?php

return [
    'name' => 'Test task in the avito real estate unit',
    'modules' => [
        'Main',
    ],
    'components' => [
        'paths' => [
            'class' => \Phact\Components\Path::class,
            'calls' => [
                'setPaths' => [
                    [
                        'base' => realpath(__DIR__ . '/../'),
                        'www' => realpath(__DIR__ . '/../../www'),
                        'root' => realpath(__DIR__ . '/../../'),
                    ]
                ]
            ]
        ],
        'translate' => [
            'class' => \Phact\Translate\Translate::class,
            'calls' => [
                'setLocale' => ['ru']
            ]
        ],
        'db' => [
            'class' => \Phact\Orm\ConnectionManager::class,
            'properties' => [
                'connections' => [
                    'default' => [
                        'host' => '127.0.0.1',
                        'dbname' => 'tpl',
                        'user' => 'root',
                        'password' => '',
                        'charset' => 'utf8',
                        'driver' => 'pdo_mysql',
                    ]
                ]
            ],
        ],
        'form_manager' => [
            'class' => \Phact\Form\Configuration\ConfigurationManager::class,
        ],
        'form' => [
            'class' => \Phact\Form\Configuration\ConfigurationProvider::class,
            'constructMethod' => 'getInstance',
            'calls' => [
                'setManager' => ['@form_manager']
            ]
        ],
        'orm_manager' => [
            'class' => \Phact\Orm\Configuration\ConfigurationManager::class,
            'calls' => [
                'setCacheFieldsTimeout' => [PHACT_DEBUG ? null : 86400]
            ]
        ],
        'orm' => [
            'class' => \Phact\Orm\Configuration\ConfigurationProvider::class,
            'constructMethod' => 'getInstance',
            'calls' => [
                'setManager' => ['@orm_manager']
            ]
        ],
        'errorHandler' => [
            'class' => \Phact\Main\ErrorHandler::class,
            'arguments' => [
                'debug' => PHACT_DEBUG
            ]
        ],
        'logger.default' => [
            'class' => \Monolog\Logger::class,
            'arguments' => [
                'name' => 'default'
            ],
            'calls' => [
                'pushHandler' => ['@default_logger_handler']
            ]
        ],
        'default_logger_handler' => [
            'class' => \Monolog\Handler\RotatingFileHandler::class,
            'arguments' => [
                'filename' => realpath(__DIR__ . '/../runtime') . '/default.log',
                'maxFiles' => 7,
                'level' => PHACT_DEBUG ? \Monolog\Logger::DEBUG : \Monolog\Logger::WARNING
            ]
        ],
        'event' => [
            'class' => \Phact\Event\EventManager::class
        ],
        'session' => [
            'class' => \Phact\Request\Session::class
        ],
        'request' => [
            'class' => \Phact\Request\HttpRequest::class
        ],
        'cliRequest' => [
            'class' => \Phact\Request\CliRequest::class
        ],
        'router' => [
            'class' => \Phact\Router\Router::class,
            'arguments' => [
                'configPath' => 'base.config.routes'
            ],
            'properties' => [
                'cacheTimeout' => PHACT_DEBUG ? null : 86400
            ],
        ],
        'template' => [
            'class' => \Phact\Template\TemplateManager::class,
            'properties' => [
                'librariesCacheTimeout' => PHACT_DEBUG ? null : 86400,
                'forceCompile' => PHACT_DEBUG,
                'autoReload' => PHACT_DEBUG
            ]
        ],
        'storage'=>[
            'class' => \Phact\Storage\FileSystemStorage::class
        ],
        'breadcrumbs' => [
            'class' => \Phact\Components\Breadcrumbs::class
        ],
        'flash' => [
            'class' => \Phact\Components\Flash::class
        ],
        'settings' => [
            'class' => \Phact\Components\Settings::class
        ],
        'cache.default' => [
            'class' => \Phact\Cache\Drivers\File::class
        ],
    ],
    'autoloadComponents' => [
        'errorHandler',
        'translate',
        'form',
        'orm'
    ]
];