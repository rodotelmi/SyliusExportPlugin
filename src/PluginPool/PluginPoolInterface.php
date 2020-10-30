<?php


namespace Boldy\SyliusExportPlugin\PluginPool;


use Boldy\SyliusExportPlugin\Plugin\PluginInterface;

interface PluginPoolInterface
{
    /**
     * @return PluginInterface[]
     */
    public function getPlugins(): array;

    public function initPlugins(array $ids): void;

    public function getDataForId(string $id, array $resourceKeys): array;
}