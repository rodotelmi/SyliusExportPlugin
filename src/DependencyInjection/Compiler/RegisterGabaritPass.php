<?php


namespace Boldy\SyliusExportPlugin\DependencyInjection\Compiler;


use Boldy\SyliusExportPlugin\Registry\ExporterRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterGabaritPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $serviceId = 'boldy.exporters_registry';

        if (!$container->has($serviceId)) return;

        $exportersRegistry = $container->getDefinition($serviceId);
        $gabarits = [];

        foreach ($container->findTaggedServiceIds('boldy.gabarit') as $id => $attributes)
        {
            $gabaritService = $container->getDefinition($id);

            foreach ($attributes as $attribute)
            {
                if (!isset($attribute['format'])) {
                    throw new \InvalidArgumentException('Tagged gabarit ' . $id . ' needs to have a format');
                }

                if (!isset($attribute['exporter'])) {
                    throw new \InvalidArgumentException('Tagged gabarit ' . $id . ' needs to have a exporter');
                }

                $gabaritService->addMethodCall('addExporter', [$attribute['format'], new Reference($attribute['exporter'])]);

            }

            $exportersRegistry->addMethodCall('register', [$id, $gabaritService]);
            $gabarits[$id] = [
                'format' => array_map(function ($attribute) {
                    return $attribute['format'];
                }, $attributes)
            ];
        }

        $container->setParameter('boldy.gabarits', $gabarits);
    }
}