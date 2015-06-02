<?php
namespace Frogsystem\Metamorphosis\Providers;

use Aura\Router\RouterContainer;

/**
 * Class RouterServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
class RouterServiceProvider extends ServiceProvider
{
    /**
     * Register the Map and Matcher with the container
     */
    public function register()
    {
        $routerContainer = new RouterContainer();
        $this->app['Aura\Router\Map'] = $routerContainer->getMap();
        $this->app['Aura\Router\Matcher'] = $routerContainer->getMatcher();
    }
}
