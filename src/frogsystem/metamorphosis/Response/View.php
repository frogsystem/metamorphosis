<?php
namespace Frogsystem\Metamorphosis\Response;

use Frogsystem\Metamorphosis\Contracts\Renderer;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Stream;

/**
 * Class View
 * @package Frogsystem\Metamorphosis\Response
 */
class View implements Renderer, ResponseInterface
{
    use ResponseTrait;

    private $renderer;

    /**
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
        $this->stream     = new Stream('php://memory', 'wb+');
        $this->statusCode =  200;
    }

    /**
     * Renders a view with the given data.
     * @param $view
     * @param array $data
     * @return View
     */
    public function render($view, array $data)
    {
        $this->getBody()->write($this->renderer->render($view, $data));
        return $this;
    }
}
