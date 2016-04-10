<?php
namespace Frogsystem\Metamorphosis\Providers;

use Dflydev\DotAccessData\Data;
use Dflydev\DotAccessData\DataInterface;
use Frogsystem\Metamorphosis\Contracts\ConfigInterface;
use Frogsystem\Metamorphosis\Services\FileConfig;
use Frogsystem\Spawn\Container;

/**
 * Class ConfigServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Registers entries with the container.
     * @param Container $app
     */
    public function register(Container $app)
    {
        $app[DataInterface::class] = $app->factory(Data::class);
        $app[ConfigInterface::class] = $app->factory(FileConfig::class);
    }
}
