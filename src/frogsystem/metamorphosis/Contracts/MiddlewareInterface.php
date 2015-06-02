<?php
namespace Frogsystem\Metamorphosis\Contracts;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface MiddlewareInterface
 * @package Frogsystem\Metamorphosis\Contracts
 */
interface MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     */
    public function handle(ServerRequestInterface $request, ResponseInterface $response, callable $next);
}
