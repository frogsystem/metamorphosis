<?php
namespace Frogsystem\Metamorphosis\Middleware;

use Aura\Router\Matcher;
use Frogsystem\Metamorphosis\Contracts\MiddlewareInterface;
use Frogsystem\Spawn\Container;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RouterMiddleware
 * @package Frogsystem\Metamorphosis\Middleware
 */
class RouterMiddleware extends Container implements MiddlewareInterface
{
    /**
     * @var Matcher The route matcher.
     */
    protected $matcher;

    /**
     * @param ContainerInterface $delegate
     * @param Matcher $matcher
     */
    public function __construct(ContainerInterface $delegate, Matcher $matcher)
    {
        parent::__construct($delegate);
        $this->matcher = $matcher;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        // Get route
        $route = $this->matcher->match($request);

        if (!$route) {
            throw new \Exception('Not found', 404);
        }

        /** @var callable $callable */
        $callable = $route->handler;

        // Store attributes
        foreach ($route->attributes as $key => $val) {
            $request = $request->withAttribute($key, $val);
        }

        // Invoke with response and route attributes
        return $next($request, $this->invoke($callable, [$response] + $route->attributes));
    }
}
