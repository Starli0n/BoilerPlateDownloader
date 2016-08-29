<?php

// phpunit --configuration=./test/phpunit.xml --coverage-html=./report --coverage-clover=./report/clover.xml --bootstrap=./test/bootstrap.php

printf("\nStarting 'bootstrap'...\n");

require __DIR__ . '/../vendor/autoload.php';

define("WEB_SERVER_ADDRESS", sprintf('http://%s:%s', WEB_SERVER_HOST, WEB_SERVER_PORT));

$report = 'report';

function delTree($dir)
{
    if (!file_exists($dir)) {
        return;
    }

    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if ($file->isDir()) {
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($dir);

    printf("  > '%s' deleted\n", $dir);
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
            __DIR__ . '/../' . WEB_SERVER_DOCROOT
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
    register_shutdown_function(function() use ($killCommand, $pid) {
        echo PHP_EOL . sprintf('Stopping server...') . PHP_EOL;
        echo sprintf('  %s - Killing process with ID %d', date('r'), $pid) . PHP_EOL;
        exec($killCommand . $pid);
    });
}

delTree(__DIR__ . '/../' . $report);
phpServe();

printf("Finished 'bootstrap'\n\n");
