<?php


namespace Boldy\SyliusExportPlugin\Gabarit;


class OrderGabarit extends AbstractGabarit
{

    private $exporters = [];

    public function getHeaders(): array
    {
        return ['Id', 'Reference'];
    }

    public function getResourceKeys(): array
    {
        return ['Id', 'Number'];
    }
}