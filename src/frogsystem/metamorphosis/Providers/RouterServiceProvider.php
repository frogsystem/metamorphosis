<?php
namespace Frogsystem\Metamorphosis\Providers;

use Aura\Router\RouterContainer;
use Frogsystem\Spawn\Container;

/**
 * Class RouterServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
class RouterServiceProvider extends ServiceProvider
{
    /**
     * Registers entries with the container.
     * @param Container $app
     */
    public function register(Container $app)
    {
        $routerContainer = new RouterContainer();
        $app['Aura\Router\Map'] = $routerContainer->getMap();
    }
}
