<?php
namespace Frogsystem\Metamorphosis;

use Exception;
use Frogsystem\Metamorphosis\Constrains\GroupHugTrait;
use Frogsystem\Metamorphosis\Constrains\HuggableTrait;
use Frogsystem\Metamorphosis\Contracts\GroupHuggable;
use Frogsystem\Metamorphosis\Middleware\RouterMiddleware;
use Frogsystem\Metamorphosis\Providers\ConfigServiceProvider;
use Frogsystem\Metamorphosis\Providers\HttpServiceProvider;
use Frogsystem\Metamorphosis\Providers\RouterServiceProvider;
use Frogsystem\Spawn\Container;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\SapiEmitter;

/**
 * Class WebApplication
 * @property ServerRequestInterface request
 * @property EmitterInterface emitter
 * @package Frogsystem\Metamorphosis
 */
class WebApplication extends Container implements GroupHuggable
{
    use HuggableTrait;
    use GroupHugTrait;

    /**
     * @var array
     */
    private $huggables = [
        RouterServiceProvider::class,
        HttpServiceProvider::class,
        ConfigServiceProvider::class,
    ];

    protected $middleware = [
        RouterMiddleware::class
    ];

    /**
     * @param ContainerInterface $delegate
     */
    public function __construct(ContainerInterface $delegate = null)
    {
        // call parent constructor
        parent::__construct($delegate);

        // set default application instance
        $this->set(self::class, $this);
        $this->set(get_called_class(), $this);

        // set emitter
        $this->emitter = $this->factory(SapiEmitter::class);

        $this->huggables = $this->load($this->huggables);
        $this->groupHug($this->huggables);
    }

    protected function load($huggables)
    {
        // Connect Huggables
        foreach ($huggables as $key => $huggable) {
            if (is_string($huggable)) {
                $huggable = $this->make($huggable);
                $huggables[$key] = $huggable;
            }
        }

        return $huggables;
    }


    /**
     * @param ServerRequestInterface|null $request
     * @param ResponseInterface|null $response
     * @param Callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request = null, ResponseInterface $response = null, $next = null)
    {
        // Read Request if omitted
        if (is_null($request)) {
            $request = $this->get(ServerRequestInterface::class);
        }

        // Create Response if omitted
        if (is_null($response)) {
            $response = $this->get(ResponseInterface::class);
        }

        // Application is called as a regular middleware, continue with stack
        if (!is_null($next)) {
            return $this->handle($request, $response, $next);
        }

        // Nothing left to do, emit the response
        return $this->emitter->emit($this->handle($request, $response));
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param Callable $next
     * @return ResponseInterface
     */
    protected function handle(ServerRequestInterface $request, ResponseInterface $response, $next = null)
    {
        // Store latest request to container
        $this->request = $request;

        // Create final middleware
        if (is_null($next)) {
            $next = function (RequestInterface $request, ResponseInterface $response) {
                return $response;
            };
        }
        
        // Try to run through the stack, catch any errors
        try {
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
            
        } catch (Exception $exception) {
            return $this->terminate($request, $response, $exception);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param Exception $exception
     * @return HtmlResponse
     */
    public function terminate(ServerRequestInterface $request, ResponseInterface $response, Exception $exception)
    {
        // Exception template
        $template = <<<HTML
<!DOCTYPE html>
<html>
    <head>
        <title>There was an error with your application</title>
    </head>
    <body>
        <h1>Quack! Something went wrong...</h1>
        <p>
            <strong>{$exception->getMessage()}</strong>
        </p>
        <pre>{$exception->getTraceAsString()}</pre>
    </body>
</html>
HTML;

        // Create Error Response
        return new HtmlResponse($template, 501);
    }
}
