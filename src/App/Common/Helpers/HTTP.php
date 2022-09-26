<?php

declare(strict_types=1);

namespace App\Common\Helpers;

final class HTTP
{
    public static function absolutePathBase(): string
    {
        return isset($_SERVER['HTTPS']) ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST'];
    }
}
