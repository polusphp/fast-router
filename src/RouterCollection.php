<?php declare(strict_types=1);

namespace Polus\Router\FastRoute;

use FastRoute\RouteCollector;
use Polus\Router\RouterCollection as BaseRouterCollection;

class RouterCollection implements BaseRouterCollection
{
    private RouteCollector $collector;

    public function __construct(RouteCollector $collector)
    {
        $this->collector = $collector;
    }

    public function get(string $route, $handler)
    {
        $this->collector->get($route, $handler);
    }

    public function put(string $route, $handler)
    {
        $this->collector->put($route, $handler);
    }

    public function post(string $route, $handler)
    {
        $this->collector->post($route, $handler);
    }

    public function delete(string $route, $handler)
    {
        $this->collector->delete($route, $handler);
    }

    public function patch(string $route, $handler)
    {
        $this->collector->patch($route, $handler);
    }

    public function head(string $route, $handler)
    {
        $this->collector->head($route, $handler);
    }

    public function attach(string $prefix, callable $callback)
    {
        $this->collector->addGroup($prefix, function (RouteCollector $collector) use ($callback) {
            $callback(new RouterCollection($collector));
        });
    }
}
