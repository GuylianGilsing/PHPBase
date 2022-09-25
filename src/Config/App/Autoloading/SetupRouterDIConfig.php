<?php

declare(strict_types=1);

namespace Config\App\Autoloading;

use Framework\Routing\Slim4BackendRouteRegisterer;
use PHPAbstractRouter\HTTP\Attributes\Collecting\RouteAttributeCollector;
use PHPAbstractRouter\HTTP\Attributes\Collecting\RouteAttributeCollectorInterface;
use PHPAbstractRouter\HTTP\Attributes\Extracting\RouteAttributeExtractor;
use PHPAbstractRouter\HTTP\Attributes\Extracting\RouteAttributeExtractorInterface;
use PHPAbstractRouter\HTTP\BackendRouteRegistererInterface;
use PHPAbstractRouter\HTTP\Router;

final class SetupRouterDIConfig implements DIConfigInterface
{
    /**
     * @return array<key, mixed>
     */
    public function getDefinitions(): array
    {
        return [
            Router::class => \DI\autowire(Router::class),
            BackendRouteRegistererInterface::class => \DI\autowire(Slim4BackendRouteRegisterer::class),
            RouteAttributeCollectorInterface::class => \DI\autowire(RouteAttributeCollector::class),
            RouteAttributeExtractorInterface::class => \DI\autowire(RouteAttributeExtractor::class),
        ];
    }
}
