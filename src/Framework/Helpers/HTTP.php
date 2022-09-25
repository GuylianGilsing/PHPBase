<?php

declare(strict_types=1);

namespace Framework\Helpers;

final class HTTP
{
    public static function schemeAndHost(): string
    {
        return isset($_SERVER['HTTPS']) ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST'];
    }
}
