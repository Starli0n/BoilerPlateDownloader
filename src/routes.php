<?php
// Routes

$app->get('/{name}', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("BoilerPlateDownloader '/' route");

    $name = $request->getAttribute('name');

    $data = array('message' => "Hello $name");
    $response = $response->withJson($data);
    return $response;
});
