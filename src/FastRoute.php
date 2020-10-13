<?php declare(strict_types=1);

namespace Polus\Router\FastRoute;

use FastRoute\Dispatcher as FastRouteDispatcher;
use Polus\Router\Route;
use Polus\Router\RouterDispatcher;

class FastRoute implements Route
{
    private array $routeInfo;

    private string $requestMethod;

    public function __construct(array $routeInfo, string $requestMethod)
    {
        $this->routeInfo = $routeInfo;
        $this->requestMethod = $requestMethod;
    }

    public function getStatus(): int
    {
        switch ($this->routeInfo[0]) {
            case FastRouteDispatcher::NOT_FOUND:
                return RouterDispatcher::NOT_FOUND;
            case FastRouteDispatcher::METHOD_NOT_ALLOWED:
                return RouterDispatcher::METHOD_NOT_ALLOWED;
        }
        return RouterDispatcher::FOUND;
    }

    public function getAllows(): array
    {
        return $this->routeInfo[0] === FastRouteDispatcher::METHOD_NOT_ALLOWED ? $this->routeInfo[1] : [];
    }

    public function getHandler()
    {
        return $this->routeInfo[1];
    }

    public function getMethod(): string
    {
        return $this->requestMethod;
    }

    public function getAttributes(): array
    {
        return $this->routeInfo[2] ?? [];
    }
}
