<?php
namespace Frogsystem\Metamorphosis\Contracts;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ServerInterface
 * @package Frogsystem\Metamorphosis\Contracts
 */
interface ServerInterface
{
    /**
     * @param callable $callable
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(callable $callable, ServerRequestInterface $request, ResponseInterface $response);

    /**
     * @param callable $final
     * @return mixed
     */
    public function listen(callable $final = null);
}
