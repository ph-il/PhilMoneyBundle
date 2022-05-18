<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RatioProviderCompilerPass.
 */
class RatioProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $ratioProviderServiceName = (string) $container->getParameter('phil_money.ratio_provider');

        $container->getDefinition('phil_money.pair_manager')->addMethodCall(
            'setRatioProvider',
            [new Reference($ratioProviderServiceName)]
        );
    }
}
