<?php
// Routes

$app->get('/hello/{name}', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("BoilerPlateDownloader '/' hello");

    $name = $request->getAttribute('name');

    $data = array('message' => "Hello $name");
    $response = $response->withJson($data);
    return $response;
});

$app->get('/list', function ($request, $response, $args) {
    $this->logger->info("BoilerPlateDownloader '/' list");

    $files = $this->api->listDir();

    $data = array(
        'directory' => $this->api->getDownloadDirectory(),
        'files' => $files);
    return $response->withJson($data);
});

$app->put('/download', function ($request, $response, $args) {
    $this->logger->info("BoilerPlateDownloader '/' download");

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
});

$app->delete('/delete', function ($request, $response, $args) {
    $this->logger->info("BoilerPlateDownloader '/' delete");

    $files = $request->getParsedBodyParam('files');
    $this->api->deleteFiles($files);
    $files = $this->api->listDir();

    $data = array(
        'directory' => $this->api->getDownloadDirectory(),
        'files' => $files);
    return $response->withJson($data);
});
