<?php

namespace BoilerPlateDownloader\Api;

$settings = require __DIR__ . '/../../../src/settings.php';

class DownloaderTest extends \PHPUnit_Framework_TestCase
{
    private static $settings;
    private static $webAddress;
    private $downloader;

    public static function main()
    {
        $Suite = new \PHPUnit_Framework_TestSuite('BoilerPlateDownloader\Api\DownloaderTest');
        \PHPUnit_TextUI_TestRunner::run($Suite);
        unset($Suite);
    }

    public static function setUpBeforeClass()
    {
        global $settings;
        self::$settings = $settings['settings']['api'];
    }

    protected function setUp()
    {
        $this->downloader = new Downloader(self::$settings['download_path'], self::$settings['download_directory'], self::$settings['extension']);
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
        $file = $this->downloader->listDir();
        $this->downloader->deleteFiles($file);
        $this->downloader->setFileUrl(WEB_SERVER_ADDRESS . '/site.min.js');
        $this->downloader->downloadRemoteFile();
        $file = $this->downloader->listDir();
        $this->assertEquals(count($file), 1);
        $this->assertEquals($file[0], 'site.min.js.zip');
    }

    public function testDeleteFiles()
    {
        $file = $this->downloader->listDir();
        $this->downloader->deleteFiles($file);
        $file = $this->downloader->listDir();
        $this->assertEquals(count($file), 0);
    }

    public function testGetDownloadDirectory()
    {
        $this->assertEquals($this->downloader->getDownloadDirectory(), 'download/');
    }

    public function testGetDownloadFile()
    {
        $this->downloader->setFileUrl(WEB_SERVER_ADDRESS . '/site.min.js');
        $this->assertEquals($this->downloader->getDownloadFile(), 'download/site.min.js.zip');
    }
}

if (defined('PHPUnit_Static_Main')) {
    DownloaderTest::main();
}
