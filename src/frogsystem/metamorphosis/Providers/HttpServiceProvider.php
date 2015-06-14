<?php
namespace Frogsystem\Metamorphosis\Providers;

use Zend\Diactoros\ServerRequestFactory;

/**
 * Class ServerServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register the Server implementation, capture the Request and create an empty response
     */
    public function plugin()
    {
        $this->app['Psr\Http\Message\ResponseInterface']
            = $this->app->factory('Zend\Diactoros\Response');

        $this->app['Psr\Http\Message\ServerRequestInterface']
            = ServerRequestFactory::fromGlobals();

        $this->app['Zend\Diactoros\Response\EmitterInterface']
            = $this->app->factory('Zend\Diactoros\Response\SapiEmitter');
    }
}
