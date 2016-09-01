<?php

namespace BoilerPlateDownloader\Test\Api;

use BoilerPlateDownloader\Api\Router;

class RouterTest extends \LocalWebTestCase
{
    public function testHello()
    {
        $this->client->get('/hello/RouterTest');
        $response = $this->client->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals('application/json;charset=utf-8', $response->getHeader('Content-Type')[0]);
        $data = json_decode($response->getBody());
        $this->assertEquals('Hello RouterTest', $data->message);
    }

    public function testList()
    {
        $this->client->get('/list');
        $response = $this->client->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals('application/json;charset=utf-8', $response->getHeader('Content-Type')[0]);
        $data = json_decode($response->getBody());
        $this->assertInternalType('array', $data->files);
    }

    public function testDownload()
    {
        $this->client->put('/download');
        $response = $this->client->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Bad Request', $response->getReasonPhrase());
        $this->assertEquals('application/json;charset=utf-8', $response->getHeader('Content-Type')[0]);
        $data = json_decode($response->getBody());
        $this->assertEquals('The file to download was not found', $data->message);

        $data = array('file' => 'file.txt');
        $this->client->put('/download', $data);
        $response = $this->client->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Bad Request', $response->getReasonPhrase());
        $this->assertEquals('application/json;charset=utf-8', $response->getHeader('Content-Type')[0]);
        $data = json_decode($response->getBody());
        $this->assertEquals('The file provided was not a http nor a ftp file', $data->message);

        $data = array('file' => 'http://localhost/file.txt');
        $this->client->put('/download', $data);
        $response = $this->client->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Bad Request', $response->getReasonPhrase());
        $this->assertEquals('application/json;charset=utf-8', $response->getHeader('Content-Type')[0]);
        $data = json_decode($response->getBody());
        $this->assertEquals('The download has failed', $data->message);

        $data = array('file' => WEB_SERVER_ADDRESS . '/site.min.js');
        $this->client->put('/download', $data);
        $response = $this->client->response;
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Created', $response->getReasonPhrase());
        $this->assertEquals('application/json;charset=utf-8', $response->getHeader('Content-Type')[0]);
        $data = json_decode($response->getBody());
        $this->assertEquals('download/', $data->directory);
        $this->assertInternalType('array', $data->files);
    }

    /**
     * @depends testDownload
     */
    public function testDelete()
    {
        $this->client->get('/list');
        $data = json_decode($this->client->response->getBody());
        $this->assertNotEquals(0, count($data->files));

        $this->client->delete('/delete', $data);
        $response = $this->client->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals('application/json;charset=utf-8', $response->getHeader('Content-Type')[0]);
        $data = json_decode($response->getBody());
        $this->assertEquals('download/', $data->directory);
        $this->assertInternalType('array', $data->files);
        $this->assertEquals(0, count($data->files));
    }

    public function testInternalError()
    {
        $this->client->get('/internalerror');
        $response = $this->client->response;
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Internal Server Error', $response->getReasonPhrase());
        $this->assertEquals('application/json;charset=utf-8', $response->getHeader('Content-Type')[0]);
        $data = json_decode($response->getBody());
        $this->assertEquals('Internal Server Error', $data->message);
    }
}
