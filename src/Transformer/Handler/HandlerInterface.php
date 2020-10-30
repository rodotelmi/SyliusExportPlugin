<?php


namespace Boldy\SyliusExportPlugin\Transformer\Handler;

interface HandlerInterface
{
    /**
     * Sets the next handler to use in case it's not handled on the current implementation
     *
     * @param HandlerInterface $handler
     */
    public function setSuccessor(self $handler): void;

    /**
     * Loops through handlers until it gets satisfying result
     * @param $key
     * @param $value
     */
    public function handle($key, $value);
}