<?php
namespace Frogsystem\Metamorphosis;

use Frogsystem\Metamorphosis\Contracts\HttpKernelInterface;
use Frogsystem\Metamorphosis\Contracts\MiddlewareInterface;
use Frogsystem\Spawn\Application;
use Frogsystem\Spawn\Contracts\KernelInterface;
use Frogsystem\Spawn\Contracts\RunnableInterface;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class WebApplication
 * @property Contracts\ServerInterface server
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

        // set default application instance
        $this->set('Frogsystem\Metamorphosis\WebApplication', $this);
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
        // run pluggables
        foreach ($this->pluggables as $pluggable) {
            if ($pluggable instanceof RunnableInterface) {
                $pluggable->run();
            }
        }

        // start listening
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
        try {
            $response = $this->handleMiddleware($request, $response);

        // trigger error handling
        } catch (\Exception $e) {
            return $done($request, $response, $e);
        }

        // all good
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
            $next = function (ServerRequestInterface $request, ResponseInterface $response) {
                return $this->handleMiddleware($request, $response);
            };

            // Get the Middleware object
            if (is_string($middleware)) {
                $middleware = $this->make($middleware);
            }

            // run the handle method if its a Middleware
            if ($middleware instanceof MiddlewareInterface) {
                $middleware = [$middleware, 'handle'];
            }

            // invoke the middleware as callable
            return $middleware($request, $response, $next);
        }

        return $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param \Exception|null $error
     * @return ResponseInterface
     */
    public function terminate(ServerRequestInterface $request, ResponseInterface $response, \Exception $error = null)
    {
        if (!$error) {
            return $response;
        }
        // Return an error here
        $response->getBody()->write($error->getMessage());
        return $response->withStatus(404);
    }
}
