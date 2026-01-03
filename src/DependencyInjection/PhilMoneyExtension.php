<?php

declare(strict_types=1);

namespace Phil\MoneyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PhilMoneyExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('form_types.yml');

        if (in_array('twig', $config['templating']['engines'], true)) {
            $loader->load('twig_extension.yml');
        }

        if (in_array('php', $config['templating']['engines'], true)) {
            $loader->load('templating_helper.yml');
        }

        $this->remapParameters($config, $container, [
            'currencies' => 'phil_money.currencies',
            'reference_currency' => 'phil_money.reference_currency',
            'decimals' => 'phil_money.decimals',
            'enable_pair_history' => 'phil_money.enable_pair_history',
            'ratio_provider' => 'phil_money.ratio_provider',
        ]);

        $container->setParameter('phil_money.pair.storage', $config['storage']);
    }

    protected function remapParameters(array $config, ContainerBuilder $container, array $map): void
    {
        foreach ($map as $name => $paramName) {
            if (isset($config[$name])) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }
}
