<?php

namespace Mf1\TrailingSlashBundle\DependencyInjection;

use Mf1\TrailingSlashBundle\Routing\TrailingSlashRouteUpdaterInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    private const SUPPORTED_SLASH_OPTIONS = [
        TrailingSlashRouteUpdaterInterface::SLASH_ADD,
        TrailingSlashRouteUpdaterInterface::SLASH_NO_CHANGE,
        TrailingSlashRouteUpdaterInterface::SLASH_REMOVE,
    ];

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mf1_trailing_slash');

        $rootNode
            ->children()
                ->arrayNode('rules')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->validate()
                                    ->ifTrue(function(string $path) {
                                        return false === preg_match('/^\//', $path);
                                    })
                                    ->thenInvalid('The path "%s" must begin with a forward slash')
                                ->end()
                            ->end()
                            ->scalarNode('slash')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->validate()
                                    ->ifNotInArray(self::SUPPORTED_SLASH_OPTIONS)
                                    ->thenInvalid(sprintf(
                                        'The slash option %%s is not supported. Available options are: "%s"',
                                        implode('", "', self::SUPPORTED_SLASH_OPTIONS)
                                    ))
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
