<?php

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return static function (DefinitionConfigurator $configurator): void {
    $configurator->rootNode()
        ->children()
//            ->arrayNode('foo')
//                ->addDefaultsIfNotSet()
//                ->children()
//                    ->booleanNode('bar')
//                        ->defaultTrue()
//                    ->end()
//                ->end()
//            ->end()
        ->end()
    ->end();
};
