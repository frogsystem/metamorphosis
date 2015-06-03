<?php
namespace Frogsystem\Metamorphosis\Providers;

use Frogsystem\Metamorphosis\WebApplication;
use Frogsystem\Spawn\Contracts\PluggableInterface;

/**
 * Class ServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
abstract class ServiceProvider implements PluggableInterface
{
    /**
     * @var WebApplication The app container.
     */
    protected $app;

    /**
     * @param WebApplication $app
     */
    public function __construct(WebApplication $app)
    {
        $this->app = $app;
    }

    /**
     * Registers entries with the container.
     */
    abstract public function plugin();

    /**
     * Remove entries with the container.
     */
    public function unplug()
    {
    }
}
