<?php

use OpenSolid\Bus\Bridge\Doctrine\Middleware\DoctrineTransactionMiddleware;
use OpenSolid\Bus\Handler\HandlersCountPolicy;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\Middleware\LoggingMiddleware;
use OpenSolid\Bus\NativeMessageBus;
use OpenSolid\Cqs\Command\CommandBus;
use OpenSolid\Cqs\Command\NativeCommandBus;
use OpenSolid\Cqs\Query\NativeQueryBus;
use OpenSolid\Cqs\Query\QueryBus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Yceruto\Decorator\DecoratorInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container) {
    if (interface_exists(EntityManagerInterface::class)) {
        $container->services()
            ->set('cqs.command.middleware.doctrine_transaction', DoctrineTransactionMiddleware::class)
            ->args([
                service('doctrine'),
            ])
            ->tag('cqs.command.middleware')
        ;
    }

    $container->services()
        ->set('cqs.command.middleware.logger', LoggingMiddleware::class)
            ->args([
                service('logger'),
                'command',
            ])
            ->tag('cqs.command.middleware')

        ->set('cqs.query.middleware.logger', LoggingMiddleware::class)
            ->args([
                service('logger'),
                'query',
            ])
            ->tag('cqs.query.middleware')

        ->set('cqs.command.middleware.handler', HandlingMiddleware::class)
            ->args([
                abstract_arg('cqs.command.middleware.handler.locator'),
                HandlersCountPolicy::SINGLE_HANDLER,
                service(DecoratorInterface::class)->ignoreOnInvalid(),
                service('logger'),
                'Command',
            ])
            ->tag('cqs.command.middleware')

        ->set('cqs.command.bus.native', NativeMessageBus::class)
            ->args([
                tagged_iterator('cqs.command.middleware'),
            ])

        ->set('cqs.command.bus', NativeCommandBus::class)
            ->args([
                service('cqs.command.bus.native'),
            ])

        ->alias(CommandBus::class, 'cqs.command.bus')

        ->set('cqs.query.middleware.handler', HandlingMiddleware::class)
            ->args([
                abstract_arg('cqs.query.middleware.handler.locator'),
                HandlersCountPolicy::SINGLE_HANDLER,
                service(DecoratorInterface::class)->nullOnInvalid(),
                service('logger'),
                'Query',
            ])
            ->tag('cqs.query.middleware')

        ->set('cqs.query.bus.native', NativeMessageBus::class)
            ->args([
                tagged_iterator('cqs.query.middleware'),
            ])

        ->set('cqs.query.bus', NativeQueryBus::class)
            ->args([
                service('cqs.query.bus.native'),
            ])

        ->alias(QueryBus::class, 'cqs.query.bus')
    ;
};
