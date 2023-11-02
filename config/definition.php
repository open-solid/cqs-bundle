<?php

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return static function (DefinitionConfigurator $configurator): void {
    $configurator->rootNode()
        ->children()
            ->arrayNode('middleware')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('doctrine')
                        ->defaultTrue()
                    ->end()
                ->end()
            ->end()
        ->end()
    ->end();
};
