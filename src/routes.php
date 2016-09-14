<?php
// Routes
$app->get('/hello/{name}', 'BoilerPlateDownloader\Api\Router:hello');
$app->get('/list', 'BoilerPlateDownloader\Api\Router:listFiles');
$app->put('/download', 'BoilerPlateDownloader\Api\Router:download');
$app->delete('/delete', 'BoilerPlateDownloader\Api\Router:delete');
