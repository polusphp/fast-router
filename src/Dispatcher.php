<?php declare(strict_types=1);

namespace Polus\Router\FastRoute;

use FastRoute\Dispatcher as FastRouteDispatcher;
use FastRoute\RouteCollector;
use Polus\Router\Route;
use Polus\Router\RouterDispatcher;
use Psr\Http\Message\ServerRequestInterface;

final readonly class Dispatcher implements RouterDispatcher
{
    public function __construct(
        private string $dispatcherClass,
        private RouteCollector $routeCollector,
    ) {}

    public function dispatch(ServerRequestInterface $request): Route
    {
        /** @var FastRouteDispatcher $dispatcher */
        $dispatcher = new $this->dispatcherClass($this->routeCollector->getData());
        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        return new FastRoute($routeInfo, $request->getMethod());
    }
}
