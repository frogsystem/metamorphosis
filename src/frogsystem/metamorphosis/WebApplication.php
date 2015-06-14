<?php
namespace Frogsystem\Metamorphosis;

use Frogsystem\Metamorphosis\Contracts\HttpKernelInterface;
use Frogsystem\Metamorphosis\Contracts\MiddlewareInterface;
use Frogsystem\Spawn\Application;
use Frogsystem\Spawn\Contracts\KernelInterface;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
        parent::run();

        // start middleware calling
        $response = $this->handle($this->find('Psr\Http\Message\ServerRequestInterface'), [$this, 'terminate']);

        // Emit response
        $this->find('Zend\Diactoros\Response\EmitterInterface')->emit($response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable $done
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, callable $done)
    {
        // Run middleware
        try {
            $response = $this->handleMiddleware($request);

        // trigger error handling
        } catch (\Exception $e) {
            return $done($request, $this->find('Psr\Http\Message\ResponseInterface'), $e);
        }

        // all good
        return $done($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    protected function handleMiddleware(ServerRequestInterface $request)
    {
        /** @var callable|MiddlewareInterface $middleware The next middleware. */
        if ($middleware = array_pop($this->middleware)) {
            // Make the Middleware object
            if (is_string($middleware)) {
                $middleware = $this->make($middleware);
            }

            // invoke the middleware callable
            if ($middleware instanceof MiddlewareInterface) {
                $middleware = [$middleware, 'handle'];
            }
            return $middleware($request, function (ServerRequestInterface $request) {
                return $this->handleMiddleware($request);
            });
        }

        return $this->find('Psr\Http\Message\ResponseInterface');
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
