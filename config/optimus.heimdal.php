<?php

use Optimus\Heimdal\Formatters;
use Symfony\Component\HttpKernel\Exception as SymfonyException;

return [
    'add_cors_headers' => TRUE,

    // Has to be in prioritized order, e.g. highest priority first.
    'formatters'       => [
        SymfonyException\UnprocessableEntityHttpException::class => Formatters\UnprocessableEntityHttpExceptionFormatter::class,
        SymfonyException\HttpException::class                    => Formatters\HttpExceptionFormatter::class,
        Exception::class                                         => Formatters\ExceptionFormatter::class,
    ],

    'response_factory' => \Optimus\Heimdal\ResponseFactory::class,

    'reporters' => [
        'bugsnag' => [
            'class'  => \Optimus\Heimdal\Reporters\BugsnagReporter::class,
            'config' => [],
        ],
    ],

    'server_error_production' => 'An error occurred.',
];
