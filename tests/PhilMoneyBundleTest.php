<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Phil\MoneyBundle\PhilMoneyBundle;

class PhilMoneyBundleTest extends TestCase
{
    public function testBuild(): void
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container
            ->expects($this->exactly(3))
            ->method('addCompilerPass');
        $bundle = new PhilMoneyBundle();
        $bundle->build($container);
    }
}
