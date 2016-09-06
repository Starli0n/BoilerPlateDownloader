<?php

namespace BoilerPlateDownloader\Test;

require __DIR__ . '/../vendor/autoload.php';
require 'tools.php';

loadDebugEnv();

debugTestRunner('Api\DownloaderTest', '');
debugTestRunner('Api\RouterTest', 'testHello');
