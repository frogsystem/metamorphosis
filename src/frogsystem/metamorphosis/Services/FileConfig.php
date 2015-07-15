<?php
namespace Frogsystem\Metamorphosis\Services;

use Dflydev\DotAccessData\DataInterface;

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
     * @param string $path Path to the config directory.
     */
    public function __construct(DataInterface $data, $path)
    {
        $this->data = $data;
        foreach ($this->getFileIterator($path) as $config) {
            $this->set($config->getBasename('.php'), include($config->getPathname()));
        }
    }

    /**
     * Get the iterator for specified config files.
     * @param $path
     * @param string $pattern
     * @return array|\RegexIterator
     */
    protected function getFileIterator($path, $pattern = '/^.+\.php$/')
    {
        if (is_dir($path)) {
            return new \RegexIterator(new \DirectoryIterator($path), $pattern, \RegexIterator::GET_MATCH);
        }
        if (is_file($path) && 1 === preg_match($pattern, $path)) {
            return [new \SplFileInfo($path)];
        }
        return [];
    }
}
