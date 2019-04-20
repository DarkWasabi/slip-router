<?php

namespace Slip\Routing;

use InvalidArgumentException;
use Slim\Route as SlimRoute;

/**
 * Class Router
 */
class Route extends SlimRoute
{
    /**
     * Parent route groups.
     *
     * @var RouteGroup[]
     */
    protected $groups = [];

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
            throw new InvalidArgumentException('Controller namespace must be a string');
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
        $namespace = '';

        foreach ($this->groups as $id => $group) {
            if (!empty($group->getNamespace())) {
                $namespace .= $group->getNamespace().'\\';
            }
        }

        return $namespace.($this->namespace ? $this->namespace.'\\' : '');
    }

    /**
     * {@inheritDoc}
     */
    protected function resolveCallable($callable)
    {
        $namespace = $this->getNamespace();

        if (is_string($callable) && strpos($callable, ':') !== false && !empty($namespace)) {
            // if looks like Controller resolution, then append namespace to Controller classname
            $callable = $namespace.ltrim($callable, '\\');
        }

        return parent::resolveCallable($callable);
    }
}
