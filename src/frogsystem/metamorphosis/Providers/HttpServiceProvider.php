<?php
namespace Frogsystem\Metamorphosis\Providers;

use Frogsystem\Spawn\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

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
            // Return changed request if available
            if (isset($app->request)) {
                return $app->request;
            }
            return ServerRequestFactory::fromGlobals();
        };
    }
}
