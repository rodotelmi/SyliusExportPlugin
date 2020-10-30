<?php


namespace Boldy\SyliusExportPlugin\PluginPool;

use Boldy\SyliusExportPlugin\Plugin\PluginInterface;

class PluginPool implements PluginPoolInterface
{
    /** @var PluginInterface[] */
    private $plugins;

    /**
     * @param PluginInterface[] $plugins
     */
    public function __construct(array $plugins)
    {
        $this->plugins = $plugins;
    }

    public function getPlugins(): array
    {
        return $this->plugins;
    }

    public function initPlugins(array $ids): void
    {
        foreach ($this->plugins as $plugin) {
            $plugin->init($ids);
        }
    }

    public function getDataForId(string $id, array $resourceKeys): array
    {
        $result = [];

        foreach ($this->plugins as $index => $plugin) {
            $result = $this->getDataForIdFromPlugin($id, $plugin, $result, $resourceKeys);
        }

        return $result;
    }

    /**
     * @param string $id
     * @param PluginInterface $plugin
     * @param mixed[] $result
     *
     * @param array $resourceKeys
     * @return mixed[]
     */
    private function getDataForIdFromPlugin(string $id, PluginInterface $plugin, array $result, array $resourceKeys): array
    {
        foreach ($plugin->getData($id, $resourceKeys) as $exportKey => $exportValue) {
            if (false === empty($result[$exportKey])) {
                continue;
            }

            // no other plugin has delivered a value till now
            $result[$exportKey] = $exportValue;
        }

        return $result;
    }
}
