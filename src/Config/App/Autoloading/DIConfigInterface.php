<?php

declare(strict_types=1);

namespace Config\App\Autoloading;

interface DIConfigInterface
{
    /**
     * Returns an array of key => value pairs where the key can either be an env variable,
     * or class::name, and the value is a PHP-DI function.
     *
     * @return array<key, mixed>
     */
    public function getDefinitions(): array;
}
