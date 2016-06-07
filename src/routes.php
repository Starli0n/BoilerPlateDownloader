<?php
// Routes

$app->get('/hello/{name}', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("BoilerPlateDownloader '/' route");

    $name = $request->getAttribute('name');

    $data = array('message' => "Hello $name");
    $response = $response->withJson($data);
    return $response;
});

$app->put('/download', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("BoilerPlateDownloader '/' download");

    $body = $request->getBody();
    $parsedBody = $request->getParsedBody();

    $file = $request->getAttribute('file');
    /*if ($this->api->setFileUrl($file)) {
        $bOk = $this->api->downloadRemoteFile();
    }*/

    $data = array('message' => "download");
    $response = $response->withJson($data, 201);
    return $response;
});
