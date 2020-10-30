<?php


namespace Boldy\SyliusExportPlugin\Gabarit;


use Sylius\Component\Resource\Repository\RepositoryInterface;

abstract class AbstractGabarit implements GabaritInterface
{
    private $exporters = [];

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * AbstractGabarit constructor.
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getExporter(string $format)
    {
        return $this->exporters[$format];
    }

    public function addExporter(string $format, $exporter)
    {
        $this->exporters[$format] = $exporter;

        return $this;
    }
}