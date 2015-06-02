<?php
namespace Frogsystem\Metamorphosis;

use Frogsystem\Metamorphosis\Contracts\HttpKernelInterface;
use Frogsystem\Metamorphosis\Contracts\MiddlewareInterface;
use Frogsystem\Metamorphosis\Kernels\WebApplicationKernel;
use Frogsystem\Spawn\Application;
use Frogsystem\Spawn\Contracts\KernelInterface;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class WebApplication
 * @property Contracts\Server server
 * @package Frogsystem\Metamorphosis
 */
class WebApplication extends Application
{
    protected $middleware = [];

    /**
     * @param ContainerInterface $delegate
     */
    public function __construct(ContainerInterface $delegate = null)
    {
        // call parent constructor
        parent::__construct($delegate);
    }

    /**
     * @param KernelInterface $kernel
     * @return mixed
     */
    public function load(KernelInterface $kernel)
    {
        parent::load($kernel);

        // Add Middleware
        if ($kernel instanceof HttpKernelInterface) {
            $this->middleware += $kernel->getMiddleware();
        }
    }

    public function run()
    {
        $this->server->listen([$this, 'terminate']);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $done
     * @return mixed|ResponseInterface
     */
    public function handle(ServerRequestInterface $request, ResponseInterface $response, callable $done)
    {
        // Run middleware
        $response = $this->handleMiddleware($request, $response);

        // Check if done or not
        if (!$done) {
            return $response;
        }
        return $done($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return mixed|ResponseInterface
     */
    protected function handleMiddleware(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var callable|MiddlewareInterface $middleware The next middleware. */
        if ($middleware = array_pop($this->middleware)) {
            // next middleware callable
            $next = function (ResponseInterface $response) use ($request) {
                return $this->handleMiddleware($request, $response);
            };

            // Get the Middleware object
            if (is_string($middleware)) {
                $middleware = $this->make($middleware);
            }

            // run the handle method if its a Middleware
            if ($middleware instanceof MiddlewareInterface) {
                return $middleware->handle($request, $response, $next);
            }

            // invoke the middleware as callable
            return $middleware($request, $response, $next);
        }

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param null $error
     * @return ResponseInterface
     */
    public function terminate(ServerRequestInterface $request, ResponseInterface $response, $error = null)
    {
        if (!$error) {
            return $response;
        }
        // Return an error here
        return $response;
    }
}
