<?php

declare(strict_types=1);

namespace App\Presentation\Middleware;

use App\Common\Helpers\HTTP;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

final class RemoveTrailingSlashMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $overflowingSlashRegex = '~\/{2,}~';
        $trailingSlashRegex = '~\/+$~';

        $uri = $request->getUri();
        $path = $uri->getPath();
        $query = $uri->getQuery();

        // Make sure that we only redirect when invalid paths are detected
        preg_match($overflowingSlashRegex, $path, $overFlowMatches);
        preg_match($trailingSlashRegex, $path, $trailingSlashMatches);

        if ((count($overFlowMatches) <= 0 && count($trailingSlashMatches) <= 0) || $path === '/')
        {
            return $handler->handle($request);
        }

        // Fix the url and redirect
        $fixedPath = preg_replace($overflowingSlashRegex, '/', $path);
        $fixedPath = preg_replace($trailingSlashRegex, '', $fixedPath);

        $fixedPath = HTTP::absolutePathBase().$fixedPath;

        // Add query variables back to the url
        if (!strlen($query) === 0)
        {
            $fixedPath .= '?'.$query;
        }

        $response = new Response(301);
        return $response->withHeader('Location', $fixedPath);
    }
}
