<?php


namespace Boldy\SyliusExportPlugin\Gabarit;


class ProductGabarit extends AbstractGabarit
{

    public function getHeaders(): array
    {
        return ['Id', 'Nom'];
    }

    public function getResourceKeys(): array
    {
        return ['Id', 'Code'];
    }
}