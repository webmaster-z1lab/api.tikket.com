<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver'    => 'gd',
    'sizes'     => [
        'cover'          => [1280, 720],
        'square'         => [640, 640],
        'thumbnail'      => [250, 250],
        'facebook_cover' => [1200, 630],
        'basic'          => [1500, 1500],
        'landscape'      => [640, 400],
    ],
    'extension' => 'webp',
    'quality'   => 80,
    'path'      => 'images',

];
