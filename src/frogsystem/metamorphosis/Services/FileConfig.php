<?php
namespace Frogsystem\Metamorphosis\Services;

use Dflydev\DotAccessData\DataInterface;
use League\Flysystem\Filesystem;

/**
 * Class FileConfig
 * @package Frogsystem\Metamorphosis\Services
 */
class FileConfig extends Config
{
    /**
     * @var DataInterface $data The internal data storage
     */
    protected $data;

    /**
     * @var DataInterface $data The internal data storage
     */
    protected $filesystem;

    /**
     * @param DataInterface $data
     * @param Filesystem $filesystem
     */
    public function __construct(DataInterface $data, Filesystem $filesystem)
    {
        $this->data = $data;
        foreach ($filesystem->listFiles('config') as $config) {
            if ('php' === pathinfo($config, PATHINFO_EXTENSION)) {
                $this->set(basename($config, '.php'), include($config));
            }
        }
    }
}
