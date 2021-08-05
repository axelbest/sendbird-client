<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('axelbest_sendbird');
        /** @var ArrayNodeDefinition|NodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
            ->scalarNode('application_id')
            ->defaultValue('%env(your_super_secret_application_id_var)%')
            ->info('Sendbird application ID ')
            ->end()
            ->scalarNode('api_token')
            ->defaultValue('%env(your_super_secret_api_token_var)%')
            ->info('Sendbird API token')
            ->end();

        return $treeBuilder;
    }
}
