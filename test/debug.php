<?php
namespace BoilerPlateDownloader\Test;

header('Content-type: text/plain');

define('PHPUNIT_TESTSUITE', true); // Prevent the output to be flushed by ob_clean() inside the framework

require 'bootstrap.php';

function debugTestRunner($classTestName, $testMethodName = '')
{
    // Resolve namespace
    if ($classTestName[0] != '\\') {
        $classTestName = __NAMESPACE__ . '\\' . $classTestName;
    }

    if ($testMethodName != '') { // Run only one test of the classTest
        $suite = new \PHPUnit_Framework_TestSuite();
        $suite->addTest(new $classTestName($testMethodName));
    } else { // Run all tests of the classTest
        $suite = new \PHPUnit_Framework_TestSuite(new \ReflectionClass($classTestName));
    }
    \PHPUnit_TextUI_TestRunner::run($suite);
    unset($suite);
}

debugTestRunner('Api\DownloaderTest', '');
debugTestRunner('Api\RouterTest', 'testHello');
