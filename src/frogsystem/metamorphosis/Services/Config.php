<?php
namespace Frogsystem\Metamorphosis\Services;

use Dflydev\DotAccessData\DataInterface;
use Frogsystem\Metamorphosis\Contracts\ConfigInterface;

/**
 * Generic Config implementation based on DataInterface
 * @package Frogsystem\Metamorphosis\Services
 */
class Config implements ConfigInterface
{
    /**
     * @var DataInterface $data The internal data storage
     */
    protected $data;

    /**
     * @param DataInterface $data
     */
    public function __construct(DataInterface $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function append($name, $value = null)
    {
        $this->data->append($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value = null)
    {
        $this->data->set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        return $this->data->get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        $this->data->remove($name);
    }

    /**
     * {@inheritdoc}
     * @internal
     */
    public function offsetExists($offset)
    {
        return !is_null($this->get($offset));
    }

    /**
     * {@inheritdoc}
     * @internal
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     * @internal
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     * @internal
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
