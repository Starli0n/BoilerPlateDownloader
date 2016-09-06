<?php

namespace BoilerPlateDownloader\Test;

function loadBootstrapEnv($bBuildInServer = true)
{
    echo PHP_EOL . sprintf('Starting \'bootstrap\'...') . PHP_EOL;

    // Settings to make all errors more obvious during testing
    error_reporting(-1);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    date_default_timezone_set('UTC');

    define('PROJECT_ROOT', realpath(__DIR__ . '/..'));
    define("WEB_SERVER_HOST", 'localhost');
    define("WEB_SERVER_PORT", '8090');
    define("WEB_SERVER_DOCROOT", './publish/');
    define("WEB_SERVER_ADDRESS", sprintf('http://%s:%s', WEB_SERVER_HOST, WEB_SERVER_PORT));
    define('REPORT_DIR', PROJECT_ROOT . '/report');

    if (delTree(REPORT_DIR)) {
        echo sprintf('  > \'%s\' deleted', REPORT_DIR) . PHP_EOL;
    }

    if ($bBuildInServer) {
        phpServe();
    }

    echo sprintf('Finished \'bootstrap\'') . PHP_EOL . PHP_EOL;
}

function loadDebugEnv()
{
    loadBootstrapEnv();
    header('Content-type: text/plain');
    define('PHPUNIT_TESTSUITE', true); // Prevent the output to be flushed by ob_clean() inside the framework
}

function delTree($dir)
{
    if (!file_exists($dir)) {
        return false;
    }

    $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        if ($file->isDir()) {
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($dir);
    return true;
}

function phpServe()
{
    // OS detection
    $isWindows = stristr(php_uname('s'), 'Windows') !== false;

    // Command that starts the built-in web server
    if ($isWindows) {
        $command = sprintf(
            'wmic process call create "php -S %s:%d -t %s" | find "ProcessId"',
            WEB_SERVER_HOST,
            WEB_SERVER_PORT,
            realpath(PROJECT_ROOT . '/' . WEB_SERVER_DOCROOT)
        );
        $killCommand = 'taskkill /f /pid ';
    } else {
        $command = sprintf(
            'php -S %s:%d -t %s >/dev/null 2>&1 & echo $!',
            WEB_SERVER_HOST,
            WEB_SERVER_PORT,
            WEB_SERVER_DOCROOT
        );
        $killCommand = 'kill ';
    }

    // Execute the command and store the process ID
    $output = array();
    echo sprintf('Starting server...') . PHP_EOL;
    echo sprintf('  Current directory: %s', getcwd()) . PHP_EOL;
    echo sprintf('  %s', $command) . PHP_EOL;
    exec($command, $output);

    // Get PID
    if ($isWindows) {
        $pid = explode('=', $output[0]);
        $pid = str_replace(' ', '', $pid[1]);
        $pid = str_replace(';', '', $pid);
    } else {
        $pid = (int) $output[0];
    }

    // Log
    echo sprintf(
        '  %s - Web server started on %s:%d with PID %d',
        date('r'),
        WEB_SERVER_HOST,
        WEB_SERVER_PORT,
        $pid
    ) . PHP_EOL;

    // Kill the web server when the process ends
    register_shutdown_function(function () use ($killCommand, $pid) {
        echo PHP_EOL . sprintf('Stopping server...') . PHP_EOL;
        echo sprintf('  %s - Killing process with ID %d', date('r'), $pid) . PHP_EOL;
        exec($killCommand . $pid);
    });
}

class LocalWebTestCase extends \There4\Slim\Test\WebTestCase
{
    public function getSlimInstance()
    {
        // Instantiate the app
        $settings = require PROJECT_ROOT . '/src/settings.php';
        $app = new \Slim\App($settings);

        // Set up dependencies
        require PROJECT_ROOT . '/src/dependencies.php';

        // Register middleware
        require PROJECT_ROOT . '/src/middleware.php';

        // Register routes
        require PROJECT_ROOT . '/src/routes.php';

        // Route for testing server internal error (ie: $container['errorHandler'])
        $app->get('/internalerror', function ($request, $response, $args) {
            $this->logger->info("Route '/' internalerror");
            throw new \Exception('Testing /internalerror.');
            return $response;
        });

        return $app;
    }
}

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
