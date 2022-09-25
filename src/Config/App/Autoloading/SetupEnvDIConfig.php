<?php

declare(strict_types=1);

namespace Config\App\Autoloading;

use ErrorException;

final class SetupEnvDIConfig implements DIConfigInterface
{
    /**
     * @throws ErrorException When the key is empty.
     *
     * @return array<key, mixed>
     */
    public function getDefinitions(): array
    {
        $loader = \Dotenv\Dotenv::createImmutable([__DIR__.'/../../../../'], ['.env']);
        $loader->load();

        return [];
    }
}
