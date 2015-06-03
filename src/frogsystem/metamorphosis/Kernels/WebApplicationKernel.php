<?php
namespace Frogsystem\Metamorphosis\Kernels;

/**
 * Class HttpKernel
 * @package Frogsystem\Metamorphosis
 */
class WebApplicationKernel extends HttpKernel
{
    /**
     * @var array
     */
    protected $middleware = [
        'Frogsystem\Metamorphosis\Middleware\RouterMiddleware',
    ];

    /**
     * @var array
     */
    protected $pluggables = [
        'Frogsystem\Metamorphosis\Providers\RouterServiceProvider',
        'Frogsystem\Metamorphosis\Providers\ServerServiceProvider',
    ];
}
