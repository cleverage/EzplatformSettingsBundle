<?php

namespace Ezplatform\SettingsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private $bundles;

    /**
     * Constructor
     *
     * @param array $bundles An array of bundle names
     */
    public function __construct(array $bundles)
    {
        $this->bundles = $bundles;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('cleverage_settings');
        //$rootNode = $treeBuilder->getRootNode();

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('cleverage_settings');
        }

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('config_file_parser')->defaultValue('\Ezplatform\SettingsBundle\Parser\XmlFileLoader')->end()
            ->arrayNode('mysql')
            ->info('mysql access')
            ->children()
            ->scalarNode('url')->defaultValue("")->isRequired()->end()
            ->end()
            ->end()
            ->arrayNode('http_cache_purge')
            ->children()
            ->booleanNode('enabled')->defaultFalse()->end()
            ->end()
            ->end()
            ->arrayNode('form')
            ->info('Form settings')
            ->children()
            ->end()
            ->end()
            ->arrayNode('bundles')
            ->defaultValue($this->bundles)
            ->prototype('scalar')
            ->validate()
            ->ifNotInArray($this->bundles)
            ->thenInvalid('%s is not a valid bundle.')
            ->end()
            ->end()
            ->end();


        return $treeBuilder;
    }
}