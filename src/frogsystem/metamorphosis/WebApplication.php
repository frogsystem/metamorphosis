<?php
namespace Frogsystem\Metamorphosis;

use Frogsystem\Metamorphosis\Contracts\HttpKernelInterface;
use Frogsystem\Spawn\Application;
use Frogsystem\Spawn\Contracts\KernelInterface;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmitterInterface;

/**
 * Class WebApplication
 * @property ServerRequestInterface request
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
        $this->set(self::class, $this);
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

    /**
     * @param ServerRequestInterface|null $request
     * @param ResponseInterface|null $response
     * @param Callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request = null, ResponseInterface $response = null, $next = null)
    {
        parent::run();

        // Read Request if omitted
        if (is_null($request)) {
            $request = $this->get(ServerRequestInterface::class);
        }

        // Create Response if omitted
        if (is_null($response)) {
            $response = $this->get(ResponseInterface::class);
        }

        if (is_null($next)) {
            $next = function (RequestInterface $request, ResponseInterface $response) {
                return $response;
            };
        }

        $response = $this->handle($request, $response, $next);
        return $this->get(EmitterInterface::class)->emit($response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param Callable $next
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        // set request to
        $this->request = $request;

        /** @var callable $middleware The next middleware. */
        if ($middleware = array_pop($this->middleware)) {
            // Make the middleware
            if (is_string($middleware)) {
                $middleware = $this->make($middleware);
            }

            // Run the next Middleware
            return $middleware($request, $response, function (ServerRequestInterface $request, ResponseInterface $response) use ($next) {
                return $this->handle($request, $response, $next);
            });
        }

        return $next($request, $response);
    }
}
