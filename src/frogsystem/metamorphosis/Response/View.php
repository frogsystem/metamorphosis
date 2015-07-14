<?php
namespace Frogsystem\Metamorphosis\Response;

use Frogsystem\Metamorphosis\Contracts\RendererInterface;
use Zend\Diactoros\Response;

/**
 * Class View
 * @package Frogsystem\Metamorphosis\Response
 */
class View extends Response
{
    private $renderer;

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        parent::__construct();
        $this->renderer = $renderer;
    }

    /**
     * Set a new renderer to the view
     * @param RendererInterface $renderer
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Renders a view with the given data.
     * @param $view
     * @param array $data
     * @return View
     */
    public function render($view, array $data = [])
    {
        $this->getBody()->write($this->renderer->render($view, $data));
        return $this;
    }
}
