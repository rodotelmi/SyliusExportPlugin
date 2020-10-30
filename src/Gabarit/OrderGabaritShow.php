<?php


namespace Boldy\SyliusExportPlugin\Gabarit;


class OrderGabaritShow extends AbstractGabarit
{

    public function getHeaders(): array
    {
        return ['Etat', 'Total'];
    }

    public function getResourceKeys(): array
    {
        return ['State', 'Total'];
    }
}