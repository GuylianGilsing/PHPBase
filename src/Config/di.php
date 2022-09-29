<?php

declare(strict_types=1);

use Config\App\Autoloading\SetupDependenciesDIConfig;
use Config\App\Autoloading\SetupEnvDIConfig;
use Config\App\Autoloading\SetupRouterDIConfig;

return [
    new SetupEnvDIConfig(),
    new SetupRouterDIConfig(),
    new SetupDependenciesDIConfig(),
];
