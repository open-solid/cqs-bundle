<?php

use OpenSolid\Cqs\Command\Bridge\SymfonyCommandBus;
use OpenSolid\Cqs\Command\CommandBus;
use OpenSolid\Cqs\Query\Bridge\SymfonyQueryBus;
use OpenSolid\Cqs\Query\QueryBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('cqs.command.bus', SymfonyCommandBus::class)
            ->args([
                service('command.bus'),
            ])

        ->alias(CommandBus::class, 'cqs.command.bus')

        ->set('cqs.query.bus', SymfonyQueryBus::class)
            ->args([
                service('query.bus'),
            ])

        ->alias(QueryBus::class, 'cqs.query.bus')
    ;
};
