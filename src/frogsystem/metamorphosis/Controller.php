<?php
namespace Frogsystem\Metamorphosis;

use Interop\Container\ContainerInterface;

/**
 * Interface Middleware
 * @package Frogsystem\Metamorphosis\Contracts
 */
abstract class Controller
{
    /**
     * @var ContainerInterface The app container.
     */
    protected $app;

    /**
     * @param ContainerInterface $app
     */
    public function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }
}
