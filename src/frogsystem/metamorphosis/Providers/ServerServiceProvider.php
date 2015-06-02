<?php
namespace Frogsystem\Metamorphosis\Providers;

use Zend\Diactoros\ServerRequestFactory;

/**
 * Class ServerServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
class ServerServiceProvider extends ServiceProvider
{
    /**
     * Register the Server implementation, capture the Request and create an empty response
     */
    public function register()
    {
        $this->app->server
            = $this->app['Frogsystem\Metamorphosis\Contracts\ServerInterface']
            = $this->app->factory('Zend\Diactoros\Server', [[$this->app, 'handle']]);

        $this->app['Psr\Http\Message\ResponseInterface']
            = $this->app->factory('Zend\Diactoros\Response');

        $this->app['Psr\Http\Message\ServerRequestInterface']
            = ServerRequestFactory::fromGlobals();
    }
}
