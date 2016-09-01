<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'bpd',
            'path' => __DIR__ . '/../logs/app.log',
        ],

        // BoilerPlateDownloader Api settings
        'api' => [
            'download_path' =>  __DIR__ . '/../public/download/',
            'download_directory' =>  'download/',
            'extension' =>  '.zip',
        ],
    ],
];
