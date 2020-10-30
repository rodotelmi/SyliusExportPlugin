<?php


namespace Boldy\SyliusExportPlugin\Exporter;


use Boldy\SyliusExportPlugin\PluginPool\PluginPoolInterface;
use Boldy\SyliusExportPlugin\Transformer\TransformerPoolInterface;
use Boldy\SyliusExportPlugin\Writer\WriterInterface;

class OrderExporter implements ExporterInterface
{
    /** @var WriterInterface */
    protected $writer;

    /** @var PluginPoolInterface */
    protected $pluginPool;

    /** @var TransformerPoolInterface|null */
    protected $transformerPool;

    /**
     * @param WriterInterface $writer
     * @param PluginPoolInterface $pluginPool
     * @param TransformerPoolInterface|null $transformerPool
     */
    public function __construct(
        WriterInterface $writer,
        PluginPoolInterface $pluginPool,
        ?TransformerPoolInterface $transformerPool
    ) {
        $this->writer = $writer;
        $this->pluginPool = $pluginPool;
        $this->transformerPool = $transformerPool;
    }

    public function setExportFile(string $filename): void
    {
        $this->writer->setFile($filename);
    }

    public function getExportedData(): string
    {
        return $this->writer->getFileContent();
    }

    public function export(array $idsToExport, array $headers, array $resourceKeys): void
    {
        $this->pluginPool->initPlugins($idsToExport);
        $this->writer->write($headers);

        foreach ($idsToExport as $id) {
            $this->writeDataForId((string) $id, $resourceKeys);
        }
    }

    /**
     * @param int[] $idsToExport
     *
     * @param array|null $headers
     * @param array|null $resourceKeys
     * @return array[]
     */
    public function exportData(array $idsToExport, array $headers, array $resourceKeys): array
    {
        $this->pluginPool->initPlugins($idsToExport);
        $this->writer->write($headers);

        $exportIdDataArray = [];

        foreach ($idsToExport as $id) {
            $exportIdDataArray[$id] = $this->getDataForId((string) $id, $resourceKeys);
        }

        return $exportIdDataArray;
    }

    private function writeDataForId(string $id, array $resourceKeys): void
    {
        $dataForId = $this->getDataForId($id, $resourceKeys);

        $this->writer->write($dataForId);
    }

    /**
     * @param string $id
     * @param array $resourceKeys
     * @return array[]
     */
    protected function getDataForId(string $id, array $resourceKeys): array
    {
        $data = $this->pluginPool->getDataForId($id, $resourceKeys);

        if (null !== $this->transformerPool) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->transformerPool->handle($key, $value);
            }
        }

        return $data;
    }

    public function finish(): void
    {
        $this->writer->finish();
    }
}