<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\DependencyInjection;

use Exception;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SendbirdBundleExtension extends Extension implements ExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(sprintf('%s/Resources/config', dirname(__DIR__)))
        );
        $loader->load('services.yaml');

        $configuration = $this->getConfiguration($configs, $container);
        if (!$configuration instanceof ConfigurationInterface) {
            throw new Exception('Cannot get configuration.');
        }

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('axelbest_sendbird.application_id', $config['application_id']);
        $container->setParameter('axelbest_sendbird.api_token', $config['api_token']);
    }

    public function getAlias(): string
    {
        return 'axelbest_sendbird';
    }
}
