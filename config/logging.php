<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [

        /* === custom channels === */

        'aqi' => [
            'driver' => 'daily',
            'path' => storage_path('logs/aqi/laravel.log'),
            'days' => 14,
            'permission' => 0664,
        ],

        'activities' => [
            'driver' => 'daily',
            'path' => storage_path('logs/activities/laravel.log'),
            'days' => 14,
            'permission' => 0664,
        ],

        'controller' => [
            'driver' => 'daily',
            'path' => storage_path('logs/controller/laravel.log'),
            'days' => 14,
            'permission' => 0664,
        ],

        'service' => [
            'driver' => 'daily',
            'path' => storage_path('logs/service/laravel.log'),
            'days' => 14,
            'permission' => 0664,
        ],

        'repository' => [
            'driver' => 'daily',
            'path' => storage_path('logs/repository/laravel.log'),
            'days' => 14,
            'permission' => 0664,
        ],

        'event' => [
            'driver' => 'daily',
            'path' => storage_path('logs/event/laravel.log'),
            'days' => 14,
            'permission' => 0664,
        ],

        'login' => [
            'driver' => 'daily',
            'path' => storage_path('logs/login/laravel.log'),
            'days' => 14,
            'permission' => 0664,
        ],

        'strava' => [
            'driver' => 'daily',
            'path' => storage_path('logs/strava/laravel.log'),
            'days' => 14,
            'permission' => 0664,
        ],

        'weather' => [
            'driver' => 'daily',
            'path' => storage_path('logs/weather/laravel.log'),
            'days' => 14,
            'permission' => 0664,
        ],

        /* === default channels === */

        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'slack'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'irunning-server-api-' . env('APP_ENV'),
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],
    ],

];
