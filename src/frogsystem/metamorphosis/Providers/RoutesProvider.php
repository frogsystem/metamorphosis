<?php
namespace Frogsystem\Metamorphosis\Providers;

use Aura\Router\Map;
use Frogsystem\Metamorphosis\Constrains\HuggableTrait;
use Frogsystem\Metamorphosis\Contracts\Huggable;
use Frogsystem\Spawn\Container;
use Interop\Container\ContainerInterface;

/**
 * Class ServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
abstract class RoutesProvider implements Huggable
{
    use HuggableTrait {
        hug as protected returnHug;
    }

    /**
     * @var Container The app container.
     */
    protected $app;

    /**
     * @var string The base namespace of the controllers.
     */
    protected $namespace;

    /**
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param $controller
     * @param $method
     * @return callable
     */
    public function controller($controller, $method)
    {
        // Prepend namespace
        if ($this->namespace && 0 !== strpos($controller, "\\")) {
            $controller = $this->namespace . "\\" . $controller;
        }

        // Add controller to app if necessary
        if (!$this->app->has($controller)) {
            $this->app[$controller] = $this->app->one($controller);
        }

        // Return closure
        $controller = $this->app->get($controller);
        return [$controller, $method];
    }

    /**
     * Implementation of the HuggableInterface
     * @param Huggable $huggable
     */
    public function hug(Huggable $huggable)
    {
        // If huggable is a container
        if ($huggable instanceof ContainerInterface && $huggable->has(Map::class)) {
            $this->registerRoutes($huggable->get(Map::class));
        }
        $this->returnHug($this);
    }

    /**
     * @param Map $map
     * @return mixed
     */
    abstract protected function registerRoutes(Map $map);
}
