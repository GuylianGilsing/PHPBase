<?php

declare(strict_types=1);

use App\Application;

require_once __DIR__.'/../src/bootstrap.php';

$dependencyContainer = getDependencyContainer();

$application = new Application($dependencyContainer);
$application->run();
