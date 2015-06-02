<?php
namespace Frogsystem\Metamorphosis\Providers;

use Aura\Router\Map;
use Frogsystem\Spawn\Container;
use Frogsystem\Spawn\Contracts\ServiceProviderInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
abstract class RouteServiceProvider implements ServiceProviderInterface
{
    /**
     * @var Container The app container.
     */
    protected $app;

    /**
     * @var Map The route map.
     */
    protected $router;

    /**
     * @var string The base namespace of the controllers.
     */
    protected $namespace;

    /**
     * @param Container $app
     * @param Map $router
     */
    public function __construct(Container $app, Map $router)
    {
        $this->app = $app;
        $this->router = $router;
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
            $controller = $this->namespace."\\".$controller;
        }

        // Add controller to app if necessary
        if (!$this->app->has($controller)) {
            $this->app[$controller] = $this->app->one($controller);
        }

        // Return closure
        return function (ResponseInterface $response) use ($controller, $method) {
            $args = func_get_args();
            $controller = $this->app->get($controller);
            return $this->app->invoke([$controller, $method], $args);
        };
    }

    /**
     *
     */
    abstract public function register();
}
