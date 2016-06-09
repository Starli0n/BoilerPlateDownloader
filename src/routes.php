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

$app->put('/download', function ($request, $response, $args) {
    // Sample log message
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

    $data = array(
        'message' => 'Success',
        'location' => $this->api->location()
    );
    return $response->withJson($data, 201);
});
