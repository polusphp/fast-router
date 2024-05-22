<?php declare(strict_types=1);

namespace Polus\Router\FastRoute;

use FastRoute\RouteCollector;
use Polus\Adr\Interfaces\Action;
use Polus\Router\AbstractRouterCollection;
use Polus\Router\RouterCollection as BaseRouterCollection;

final class RouterCollection extends AbstractRouterCollection
{
    public function __construct(
        private readonly RouteCollector $collector,
    ) {}

    public function attach(string $prefix, callable $callback): void
    {
        $this->collector->addGroup($prefix, function (RouteCollector $collector) use ($callback) {
            $callback(new RouterCollection($collector));
        });
    }

    public function any(string $route, Action|string $handler): void
    {
        $this->collector->addRoute(['GET', 'POST', 'DELETE', 'PUT'], $route, $handler);
    }

    protected function add(string $verb, string $route, Action|string $handler): void
    {
        if (!method_exists($this->collector, $verb)) {
            throw new \BadMethodCallException(sprintf(
                'Method "%s" not implemented in fast-route',
                $verb,
            ));
        }
        $this->collector->$verb($route, $handler);
    }
}
