<?php declare(strict_types=1);

namespace Polus\Router\FastRoute;

use FastRoute\Dispatcher as FastRouteDispatcher;
use FastRoute\RouteCollector;
use Polus\Router\Route;
use Polus\Router\RouterDispatcher;
use Psr\Http\Message\ServerRequestInterface;

class Dispatcher implements RouterDispatcher
{
    private string $dispatcherClass;
    private RouteCollector $routeCollector;

    public function __construct(string $dispatcherClass, RouteCollector $routeCollector)
    {
        $this->dispatcherClass = $dispatcherClass;
        $this->routeCollector = $routeCollector;
    }

    public function dispatch(ServerRequestInterface $request): Route
    {
        /** @var FastRouteDispatcher $dispatcher */
        $dispatcher = new $this->dispatcherClass($this->routeCollector->getData());
        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        return new FastRoute($routeInfo, $request->getMethod());
    }
}
