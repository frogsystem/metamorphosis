<?php
namespace Frogsystem\Metamorphosis\Providers;

/**
 * Class ConfigServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Registers entries with the container.
     */
    public function plugin()
    {
        $this->app['Dflydev\DotAccessData\DataInterface']
            = $this->app->factory('Dflydev\DotAccessData\Data');
        $this->app['Frogsystem\Metamorphosis\Contracts\ConfigInterface']
            = $this->app->factory('Frogsystem\Metamorphosis\Services\FileConfig');
    }
}
