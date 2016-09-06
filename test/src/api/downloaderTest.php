<?php

namespace BoilerPlateDownloader\Test\Api;

use BoilerPlateDownloader\Api\Downloader;

class DownloaderTest extends \PHPUnit_Framework_TestCase
{
    private static $settings;
    private $downloader;

    public static function setUpBeforeClass()
    {
        self::$settings = require PROJECT_ROOT . '/src/settings.php';
        self::$settings = self::$settings['settings']['api'];
    }

    protected function setUp()
    {
        $this->downloader = new Downloader(
            self::$settings['download_path'],
            self::$settings['download_directory'],
            self::$settings['extension']
        );
    }

    protected function tearDown()
    {
        unset($this->downloader);
    }

    public function testStartsWith()
    {
        $this->assertTrue($this->downloader->startsWith('http://localhost', 'http'));
        $this->assertTrue($this->downloader->startsWith('ftp://localhost', 'ftp'));
        $this->assertfalse($this->downloader->startsWith('path_to_file', 'http'));
    }

    public function testEndsWith()
    {
        $this->assertTrue($this->downloader->endsWith('file.txt', 'txt'));
        $this->assertTrue($this->downloader->endsWith('file.txt', '.txt'));
        $this->assertFalse($this->downloader->endsWith('file.txt', 'zip'));
    }

    public function testSetFileUrl()
    {
        $this->assertTrue($this->downloader->setFileUrl('http://localhost'));
        $this->assertTrue($this->downloader->setFileUrl('http://localhost/file.txt'));
        $this->assertTrue($this->downloader->setFileUrl('ftp://localhost/file.txt'));
        $this->assertFalse($this->downloader->setFileUrl('file.txt'));
    }

    public function testDownloadRemoteFile()
    {
        $this->downloader->setFileUrl(WEB_SERVER_ADDRESS . '/file.txt');
        $this->assertFalse($this->downloader->downloadRemoteFile());

        $this->downloader->setFileUrl(WEB_SERVER_ADDRESS . '/site.min.js');
        $this->assertTrue($this->downloader->downloadRemoteFile());
    }

    public function testListDir()
    {
        $files = $this->downloader->listDir();
        $this->downloader->deleteFiles($files);
        $this->downloader->setFileUrl(WEB_SERVER_ADDRESS . '/site.min.js');
        $this->downloader->downloadRemoteFile();
        $files = $this->downloader->listDir();
        $this->assertEquals(1, count($files));
        $this->assertEquals('site.min.js.zip', $files[0]);
    }

    public function testDeleteFiles()
    {
        $files = $this->downloader->listDir();
        $this->downloader->deleteFiles($files);
        $files = $this->downloader->listDir();
        $this->assertEquals(0, count($files));
    }

    public function testGetDownloadDirectory()
    {
        $this->assertEquals('download/', $this->downloader->getDownloadDirectory());
    }

    public function testGetDownloadFile()
    {
        $this->downloader->setFileUrl(WEB_SERVER_ADDRESS . '/site.min.js');
        $this->assertEquals('download/site.min.js.zip', $this->downloader->getDownloadFile());
    }
}
