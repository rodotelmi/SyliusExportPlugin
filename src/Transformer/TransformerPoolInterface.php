<?php


namespace Boldy\SyliusExportPlugin\Transformer;


interface TransformerPoolInterface
{
    /**
     * @param $key
     * @param $value
     * @return mixed (Something that can cast to string at least)
     */
    public function handle($key, $value);
}