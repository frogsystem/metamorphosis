<?php
namespace Frogsystem\Metamorphosis\Providers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;

/**
 * Class HttpServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register the Server implementation, capture the Request and create an empty response
     */
    public function plugin()
    {
        $this->app[ResponseInterface::class]
            = $this->app->factory(Response::class);

        $this->app[ServerRequestInterface::class] = function () {
            if (isset($this->app->request)) {
                return $this->app->request;
            }
            return ServerRequestFactory::fromGlobals();
        };

        $this->app[EmitterInterface::class]
            = $this->app->factory(SapiEmitter::class);
    }
}
