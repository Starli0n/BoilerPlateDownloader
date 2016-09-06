<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function (Slim\Container $c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function (Slim\Container $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// BoilerPlateDownloader
$container['api'] = function (Slim\Container $c) {
    $settings = $c->get('settings')['api'];
    return new BoilerPlateDownloader\Api\Downloader(
        $settings['download_path'],
        $settings['download_directory'],
        $settings['extension']
    );
};

// Error handler
$container['errorHandler'] = function (Slim\Container $c) {
    return function ($request, $response, $exception) use ($c) {
        $c->logger->error('Internal Server Error');
        $data = array('message' => 'Internal Server Error');
        return $c['response']->withJson($data, 500);
    };
};
