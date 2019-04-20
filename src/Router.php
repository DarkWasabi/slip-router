<?php

namespace Slip\Routing;

use Slim\Router as SlimRouter;

/**
 * Class Router
 */
class Router extends SlimRouter
{
    /**
     * Add a route group to the array
     *
     * @param string   $pattern
     * @param callable $callable
     *
     * @return RouteGroup
     */
    public function pushGroup($pattern, $callable)
    {
        $group = new RouteGroup($pattern, $callable);

        array_push($this->routeGroups, $group);

        return $group;
    }

    /**
     * Removes the last route group from the array
     *
     * @return RouteGroup|bool The RouteGroup if successful, else False
     */
    public function popGroup()
    {
        $group = array_pop($this->routeGroups);

        return $group instanceof RouteGroup ? $group : false;
    }

    /**
     * Create a new Route object
     *
     * @param  string[] $methods Array of HTTP methods
     * @param  string   $pattern The route pattern
     * @param  callable $callable The route callable
     *
     * @return Route
     */
    protected function createRoute($methods, $pattern, $callable)
    {
        $route = new Route($methods, $pattern, $callable, $this->routeGroups, $this->routeCounter);

        if (!empty($this->container)) {
            $route->setContainer($this->container);
        }

        return $route;
    }
}
