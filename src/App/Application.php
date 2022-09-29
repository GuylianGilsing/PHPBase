<?php

declare(strict_types=1);

namespace App;

use App\Presentation\Controllers\ParkController;
use App\Presentation\Middleware\RemoveTrailingSlashMiddleware;
use Framework\AbstractApplication;
use PHPAbstractRouter\HTTP\Router;
use Slim\App;

final class Application extends AbstractApplication
{
    protected function setupApplication(App $app): void
    {
        if (array_key_exists('APP_DEBUG', $_ENV))
        {
            $this->inProduction = $_ENV['APP_DEBUG'] === 'false' ? true : false;
        }

        $app->addRoutingMiddleware();
        $app->addBodyParsingMiddleware();
        $app->addErrorMiddleware(!$this->inProduction, !$this->inProduction, !$this->inProduction);

        $app->add(new RemoveTrailingSlashMiddleware());
    }

    protected function setupRouting(Router $router): void
    {
        // TODO:: Define routes...
    }
}
