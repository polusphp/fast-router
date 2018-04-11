<?php
declare(strict_types=1);

namespace Polus\Router\FastRoute;

use Polus\Router\RouteInterface;
use Polus\Router\RouterDispatcherInterface;
use FastRoute\Dispatcher as FastRouteDispatcher;
use Psr\Http\Message\ServerRequestInterface;

class Dispatcher implements RouterDispatcherInterface
{
    /** @var string */
    private $dispatcherClass;
    /** @var \FastRoute\RouteCollector */
    private $routeCollector;

    public function __construct(string $dispatcherClass, \FastRoute\RouteCollector $routeCollector)
    {
        $this->dispatcherClass = $dispatcherClass;
        $this->routeCollector = $routeCollector;
    }

    public function dispatch(ServerRequestInterface $request): RouteInterface
    {
        /** @var FastRouteDispatcher $dispatcher */
        $dispatcher = new $this->dispatcherClass($this->routeCollector->getData());
        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        return new Route($routeInfo, $request->getMethod());
    }
}
