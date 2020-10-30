<?php

declare(strict_types=1);

namespace Boldy\SyliusExportPlugin;

use Boldy\SyliusExportPlugin\DependencyInjection\Compiler\RegisterGabaritPass;
use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BoldySyliusExportPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RegisterGabaritPass());
    }

}
