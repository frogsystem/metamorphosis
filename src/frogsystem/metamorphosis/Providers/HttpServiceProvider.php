<?php
namespace Frogsystem\Metamorphosis\Providers;

use Frogsystem\Spawn\Container;
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
     * Registers entries with the container.
     * @param Container $app
     */
    public function register(Container $app)
    {
        $app[ResponseInterface::class]
            = $app->factory(Response::class);

        $app[ServerRequestInterface::class] = function () use ($app) {
//            if (isset($app->request)) {
//                return $app->request;
//            }
            return ServerRequestFactory::fromGlobals();
        };

        $app[EmitterInterface::class] = $app->factory(SapiEmitter::class);
    }
}
