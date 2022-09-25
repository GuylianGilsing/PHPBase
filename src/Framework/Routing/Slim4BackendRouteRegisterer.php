<?php

declare(strict_types=1);

namespace Framework\Routing;

use PHPAbstractRouter\HTTP\Abstractions\HTTPRoute;
use PHPAbstractRouter\HTTP\Abstractions\HTTPRouteGroup;
use PHPAbstractRouter\HTTP\BackendRouteRegistererInterface;
use Slim\App;
use Slim\Interfaces\RouteGroupInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Routing\RouteCollectorProxy;

final class Slim4BackendRouteRegisterer implements BackendRouteRegistererInterface
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function route(HTTPRoute $route): void
    {
        $slimRoute = $this->app->map(
            [$route->getMethod()],
            $route->getPath(),
            $this->createRouteCallable($route)
        );

        $this->registerMiddlewareOnRoute($slimRoute, $route->getMiddlewareStack());
    }

    public function routeGroup(HTTPRouteGroup $group): void
    {
        $slimGroup = $this->app->group($group->getPath(), function (RouteCollectorProxy $groupProxy) use ($group): void
        {
            foreach ($group->getAllRoutes() as $routeToRegister)
            {
                $slimRoute = $groupProxy->map(
                    [$routeToRegister->getMethod()],
                    $routeToRegister->getPath(),
                    $this->createRouteCallable($routeToRegister)
                );

                $this->registerMiddlewareOnRoute($slimRoute, $routeToRegister->getMiddlewareStack());
            }
        });

        $this->registerMiddlewareOnRouteGroup($slimGroup, $group->getMiddlewareStack());
    }

    private function createRouteCallable(HTTPRoute $route): string
    {
        if (strlen($route->getClassMethod()) === 0)
        {
            return $route->getClassName();
        }

        return $route->getClassName().':'.$route->getClassMethod();
    }

    /**
     * @param array<string> $middlewareStack An array of `CLASS_NAME_HERE::class` strings.
     * Middleware will be invoked through class magic methods,
     */
    private function registerMiddlewareOnRoute(RouteInterface $route, array $middlewareStack): void
    {
        foreach ($middlewareStack as $middleware)
        {
            $route->add($middleware);
        }
    }

    /**
     * @param array<string> $middlewareStack An array of `CLASS_NAME_HERE::class` strings.
     * Middleware will be invoked through class magic methods,
     */
    private function registerMiddlewareOnRouteGroup(RouteGroupInterface $group, array $middlewareStack): void
    {
        foreach ($middlewareStack as $middleware)
        {
            $group->add($middleware);
        }
    }
}
