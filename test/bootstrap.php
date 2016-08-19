<?php

// phpunit --coverage-html=./report --bootstrap=./bootstrap.php

printf("\nStarting 'bootstrap'...\n");

require __DIR__ . '/../vendor/autoload.php';

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

delTree(__DIR__ . '/../' . $report);

printf("Finished 'bootstrap'\n\n");
