<?php
namespace Frogsystem\Metamorphosis\Providers;

use Frogsystem\Metamorphosis\Contracts\Huggable;
use Frogsystem\Metamorphosis\Constrains\HuggableTrait;
use Frogsystem\Spawn\Container;

/**
 * Class ServiceProvider
 * @package Frogsystem\Metamorphosis\Providers
 */
abstract class ServiceProvider implements Huggable
{
    use HuggableTrait {
        hug as protected returnHug;
    }

    /**
     * Implementation of the HuggableInterface
     * @param Huggable $huggable
     */
    public function hug(Huggable $huggable)
    {
        // If huggable is a container
        if ($huggable instanceof Container) {
            $this->register($huggable);
        }
        $this->returnHug($this);
    }

    /**
     * Registers entries with the container.
     * @param Container $app
     */
    abstract public function register(Container $app);
}
