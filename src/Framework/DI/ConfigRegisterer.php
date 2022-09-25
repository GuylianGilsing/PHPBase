<?php

declare(strict_types=1);

namespace Framework\DI;

use Config\App\Autoloading\DIConfigInterface;
use DI\ContainerBuilder;

final class ConfigRegisterer
{
    private ContainerBuilder $builder;

    public function __construct(ContainerBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function register(DIConfigInterface $config): void
    {
        $this->builder->addDefinitions($config->getDefinitions());
    }
}
