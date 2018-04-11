<?php
declare(strict_types=1);

namespace Polus\Router\FastRoute;

use Polus\Router\RouteInterface;
use Polus\Router\RouterDispatcherInterface;
use FastRoute\Dispatcher;

class Route implements RouteInterface
{
    private $routeInfo;
    private $requestMethod;

    public function __construct(array $routeInfo, string $requestMethod)
    {
        $this->routeInfo = $routeInfo;
        $this->requestMethod = $requestMethod;
    }

    public function getStatus(): int
    {
        switch ($this->routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return RouterDispatcherInterface::NOT_FOUND;
            case Dispatcher::METHOD_NOT_ALLOWED:
                return RouterDispatcherInterface::METHOD_NOT_ALLOWED;
        }
        return RouterDispatcherInterface::FOUND;
    }

    public function getAllows(): array
    {
        return $this->routeInfo[0] === Dispatcher::METHOD_NOT_ALLOWED ? $this->routeInfo[1] : [];
    }

    public function getHandler()
    {
        return $this->routeInfo[1];
    }

    public function getMethod()
    {
        return $this->requestMethod;
    }

    public function getAttributes(): array
    {
        return $this->routeInfo[2]??[];
    }
}
