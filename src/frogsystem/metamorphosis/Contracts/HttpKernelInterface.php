<?php
namespace Frogsystem\Metamorphosis\Contracts;

use Frogsystem\Spawn\Contracts\KernelInterface;

/**
 * Interface HttpKernelInterface
 * @package Frogsystem\Metamorphosis\Contracts
 */
interface HttpKernelInterface extends KernelInterface
{
    /**
     * @return mixed
     */
    public function getMiddleware();
}
