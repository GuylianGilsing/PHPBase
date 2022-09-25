<?php

declare(strict_types=1);

namespace Framework;

use DI\Bridge\Slim\Bridge;
use DI\Container;
use PHPAbstractRouter\HTTP\Router;
use Slim\App;

abstract class AbstractApplication
{
    protected bool $inProduction = false;

    private ?App $slimApp = null;
    private ?Container $dependencyContainer = null;

    public function __construct(Container $container)
    {
        $this->dependencyContainer = $container;
        $this->slimApp = $this->createSlimpApp();

        $this->dependencyContainer->set(App::class, $this->slimApp);

        $this->setupApplication($this->slimApp);
        $this->setupRouting($this->dependencyContainer->get(Router::class));
    }

    public function run(): void
    {
        $this->slimApp->run();
    }

    abstract protected function setupApplication(App $app): void;
    abstract protected function setupRouting(Router $router): void;

    private function createSlimpApp(): App
    {
        return Bridge::create($this->dependencyContainer);
    }
}
