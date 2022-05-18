<?php

declare(strict_types=1);

namespace Phil\MoneyBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Phil\MoneyBundle\DependencyInjection\Compiler\PairHistoryCompilerPass;
use Phil\MoneyBundle\DependencyInjection\Compiler\RatioProviderCompilerPass;
use Phil\MoneyBundle\DependencyInjection\Compiler\StorageCompilerPass;

/**
 * Class PhilMoneyBundle.
 */
class PhilMoneyBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new StorageCompilerPass());
        $container->addCompilerPass(new PairHistoryCompilerPass());
        $container->addCompilerPass(new RatioProviderCompilerPass());
    }
}
