<?php
namespace Frogsystem\Metamorphosis\Kernels;

/**
 * Class HttpKernel
 * @package Frogsystem\Metamorphosis
 */
class WebApplicationKernel extends HttpKernel
{
    /**
     * Get a list of all Kernel Pluggables
     * @return array
     */
    public function getPluggables()
    {
        return array_merge([
            'Frogsystem\Metamorphosis\Providers\RouterServiceProvider',
            'Frogsystem\Metamorphosis\Providers\HttpServiceProvider',
            'Frogsystem\Metamorphosis\Providers\ConfigServiceProvider',
        ], $this->pluggables);
    }

    /**
     * @return mixed
     */
    public function getMiddleware()
    {
        return array_merge([
            'Frogsystem\Metamorphosis\Middleware\RouterMiddleware',
        ], $this->middleware);
    }
}
