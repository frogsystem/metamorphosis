<?php
namespace Frogsystem\Metamorphosis\Response;

use Frogsystem\Metamorphosis\Contracts\Renderer;
use Zend\Diactoros\Response;

/**
 * Class View
 * @package Frogsystem\Metamorphosis\Response
 */
class View extends Response
{
    private $renderer;

    /**
     * @param Renderer $renderer
     */
    public function __construct(Renderer $renderer)
    {
        parent::__construct();
        $this->renderer = $renderer;
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
