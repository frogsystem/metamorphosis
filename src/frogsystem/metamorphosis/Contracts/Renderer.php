<?php
namespace Frogsystem\Metamorphosis\Contracts;

/**
 * Interface Renderer
 * @package Frogsystem\Metamorphosis\Contracts
 */
interface Renderer
{
    /**
     * Renders a view with the given data.
     * @param $view
     * @param array $data
     * @return string
     */
    public function render($view, array $data);
}
