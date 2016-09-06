<?php

namespace BoilerPlateDownloader\Api;

use Slim\Http\Request;
use Slim\Http\Response;

class Router
{
    private $logger;
    private $api;

    public function __construct(\Interop\Container\ContainerInterface $ci)
    {
        $this->logger = $ci->logger;
        $this->api = $ci->api;
    }

    public function hello(Request $request, Response $response, array $args): Response
    {
        // Sample log message
        $this->logger->info("Route '/' hello");

        $name = $request->getAttribute('name');

        $data = array('message' => "Hello $name");
        $response = $response->withJson($data);
        return $response;
    }

    public function list(Request $request, Response $response, array $args): Response
    {
        $this->logger->info("Route '/' list");

        $files = $this->api->listDir();

        $data = array(
            'directory' => $this->api->getDownloadDirectory(),
            'files' => $files);
        return $response->withJson($data);
    }

    public function download(Request $request, Response $response, array $args): Response
    {
        $this->logger->info("Route '/' download");

        $file = $request->getParsedBodyParam('file');

        if (is_null($file)) {
            $data = array('message' => 'The file to download was not found');
            return $response->withJson($data, 400);
        }

        if (!$this->api->setFileUrl($file)) {
            $data = array('message' => 'The file provided was not a http nor a ftp file');
            return $response->withJson($data, 400);
        }

        if (!$this->api->downloadRemoteFile()) {
            $data = array('message' => 'The download has failed');
            return $response->withJson($data, 400);
        }
        $files = $this->api->listDir();

        $data = array(
            'directory' => $this->api->getDownloadDirectory(),
            'files' => $files);
        return $response->withJson($data, 201);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $this->logger->info("Route '/' delete");

        $files = $request->getParsedBodyParam('files');
        $this->api->deleteFiles($files);
        $files = $this->api->listDir();

        $data = array(
            'directory' => $this->api->getDownloadDirectory(),
            'files' => $files);
        return $response->withJson($data);
    }
}
