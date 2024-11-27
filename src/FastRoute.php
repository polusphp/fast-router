<?php declare(strict_types=1);

namespace Polus\Router\FastRoute;

use FastRoute\Dispatcher as FastRouteDispatcher;
use Polus\Adr\Interfaces\Action;
use Polus\Router\Route;
use Polus\Router\RouteStatus;

class FastRoute implements Route
{
    private RouteStatus $routeStatus;
    /** @var list<string> */
    private array $allowedMethods = [];
    /** @var array<string, mixed> */
    private array $attributes = [];
    private string $requestMethod;
    private null|string|Action $handler;

    public function __construct(array $routeInfo, string $requestMethod)
    {
        $this->requestMethod = $requestMethod;
        $this->routeStatus = match ($routeInfo[0]) {
            FastRouteDispatcher::NOT_FOUND => RouteStatus::NotFound,
            FastRouteDispatcher::METHOD_NOT_ALLOWED => RouteStatus::MethodNotAllowed,
            default => RouteStatus::Found,
        };
        if ($this->routeStatus === RouteStatus::MethodNotAllowed) {
            $this->allowedMethods = $routeInfo[1];
        }
        elseif (isset($routeInfo[1]) && (is_string($routeInfo[1]) || $routeInfo[1] instanceof Action)) {
            $this->handler = $routeInfo[1];
            $this->attributes = $routeInfo[2] ?? [];
        }
    }

    public function getStatus(): RouteStatus
    {
        return $this->routeStatus;
    }

    public function getAllows(): array
    {
        return $this->allowedMethods;
    }

    public function getHandler(): Action|string|null
    {
        return $this->handler;
    }

    public function getMethod(): string
    {
        return $this->requestMethod;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
