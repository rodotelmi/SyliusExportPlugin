<?php


namespace Boldy\SyliusExportPlugin\Registry;


use Sylius\Component\Registry\ServiceRegistry;

class ExporterRegistry extends ServiceRegistry
{
    public static function buildServiceName(string $type, string $format): string
    {
        return sprintf('%s.%s', $type, $format);
    }
}