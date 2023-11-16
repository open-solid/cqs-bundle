<?php

use Cqs\Command\CommandBus;
use Cqs\Command\SymfonyCommandBus;
use Cqs\Query\QueryBus;
use Cqs\Query\SymfonyQueryBus;
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
