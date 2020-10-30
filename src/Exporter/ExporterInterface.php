<?php


namespace Boldy\SyliusExportPlugin\Exporter;

interface ExporterInterface
{
    /**
     * @param int[] $idsToExport
     * @param array|null $headers
     * @param array|null $resourceKeys
     */
    public function export(array $idsToExport, array $headers, array $resourceKeys): void;

    public function exportData(array $idsToExport, array $headers, array $resourceKeys): array;

    public function setExportFile(string $filename): void;

    public function getExportedData(): string;

    public function finish(): void;
}