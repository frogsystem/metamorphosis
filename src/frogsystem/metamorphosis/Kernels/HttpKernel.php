<?php
namespace Frogsystem\Metamorphosis\Kernels;

use Frogsystem\Metamorphosis\Contracts\HttpKernelInterface;
use Frogsystem\Spawn\Kernel;

/**
 * Class HttpKernel
 * @package Frogsystem\Metamorphosis
 */
abstract class HttpKernel extends Kernel implements HttpKernelInterface
{
    /**
     * @var array
     */
    protected $middleware = [];

    /**
     * @return mixed
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }
}
