<?php

namespace BoilerPlateDownloader\Api;

$settings = require __DIR__ . '/../../../src/settings.php';

class DownloaderTest extends \PHPUnit_Framework_TestCase
{
    private static $settings;
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

    public function testTest()
    {
        $this->assertEquals(0, 0);
    }
}

if (defined('PHPUnit_Static_Main')) {
    DownloaderTest::main();
}
