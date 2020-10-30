<?php


namespace Boldy\SyliusExportPlugin\Gabarit;


interface GabaritInterface
{
    public function getHeaders(): array;

    public function getResourceKeys(): array;

    public function getExporter(string $format);

    public function addExporter(string $format, $exporter);
}