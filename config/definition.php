<?php

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\Messenger\MessageBusInterface;

return static function (DefinitionConfigurator $configurator): void {
    $configurator->rootNode()
        ->children()
            ->arrayNode('bus')
                ->addDefaultsIfNotSet()
                ->children()
                    ->enumNode('strategy')
                        ->defaultValue(interface_exists(MessageBusInterface::class) ? 'symfony' : 'native')
                        ->values(['symfony', 'native', 'custom'])
                    ->end()
                ->end()
            ->end()
        ->end()
    ->end();
};
