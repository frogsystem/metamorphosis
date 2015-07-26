<?php
namespace Frogsystem\Metamorphosis\Contracts;

/**
 * Interface ConfigInterface
 * @package Frogsystem\Metamorphosis\Contracts
 */
interface ConfigInterface extends \ArrayAccess
{
    /**
     * Append a config value (assumes key refers to an array value)
     * @param string $name
     * @param mixed  $value
     */
    public function append($name, $value = null);

    /**
     * Set a config value in dot notation.
     * @param $name
     * @param $value
     */
    public function set($name, $value = null);

    /**
     * Retrieve a config value in dot notation.
     * @param $name
     * @return mixed
     */
    public function get($name);

    /**
     * Remove a config value
     * @param string $name
     */
    public function remove($name);
}
