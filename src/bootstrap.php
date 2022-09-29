<?php

declare(strict_types=1);

use Config\App\Autoloading\DIConfigInterface;
use DI\Container;
use DI\ContainerBuilder;
use Framework\DI\ConfigRegisterer;

require_once __DIR__.'/../vendor/autoload.php';

define('SRC_DIR', __DIR__);

function getDependencyContainer(): Container
{
    $builder = new ContainerBuilder();
    $configRegisterer = new ConfigRegisterer($builder);

    $diConfigs = require __DIR__.'/Config/di.php';

    if (!is_array($diConfigs))
    {
        throw new ErrorException(
            'DI config file should return an array with classses that implement the
             "'.DIConfigInterface::class.'" interface.'
        );
    }

    foreach ($diConfigs as $diConfig)
    {
        if (!($diConfig instanceof DIConfigInterface))
        {
            throw new ErrorException(
                'Given DI config does not implement the "'.DIConfigInterface::class.'" interface.'
            );
        }

        $configRegisterer->register($diConfig);
    }

    $builder->useAutowiring(true);
    $builder->useAnnotations(false);

    return $builder->build();
}
