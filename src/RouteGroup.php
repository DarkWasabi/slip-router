<?php

namespace Slip\Routing;

use InvalidArgumentException;
use Slim\RouteGroup as SlimRouteGroup;

/**
 * Class RouteGroup
 */
class RouteGroup extends SlimRouteGroup
{
    /**
     * Controller namespace.
     *
     * @var string
     */
    private $namespace;


    /**
     * Set controller namespace.
     *
     * @param string $namespace
     *
     * @return self
     *
     * @throws InvalidArgumentException if the controller namespace is not a string
     */
    public function setNamespace($namespace)
    {
        if (!is_string($namespace)) {
            throw new InvalidArgumentException('Group namespace must be a string');
        }

        $this->namespace = trim($namespace, '\\');

        return $this;
    }

    /**
     * Get controller namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}
