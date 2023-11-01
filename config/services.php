<?php

use Cqs\Command\CommandBus;
use Cqs\Command\NativeCommandBus;
use Cqs\Messenger\Middleware\HandlerMiddleware;
use Cqs\Messenger\Middleware\MiddlewareChain;
use Cqs\Messenger\NativeMessageBus;
use Cqs\Query\NativeQueryBus;
use Cqs\Query\QueryBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('cqs.command.handler_middleware', HandlerMiddleware::class)
            ->args([
                tagged_locator('cqs.command_handler', 'command'),
            ])

        ->set('cqs.command.middlewares', MiddlewareChain::class)
            ->args([
                [service('cqs.command.handler_middleware')],
            ])

        ->set('cqs.command_bus', NativeMessageBus::class)
            ->args([
                service('cqs.command.middlewares'),
            ])

        ->set(NativeCommandBus::class)
            ->args([
                service('cqs.command_bus'),
            ])

        ->alias(CommandBus::class, NativeCommandBus::class)

        ->set('cqs.query.handler_middleware', HandlerMiddleware::class)
            ->args([
                tagged_locator('cqs.query_handler', 'query'),
            ])
            ->tag('cqs.middleware')

        ->set('cqs.query.middlewares', MiddlewareChain::class)
            ->args([
                tagged_iterator('cqs.middleware'),
            ])

        ->set('cqs.query_bus', NativeMessageBus::class)
            ->args([
                service('cqs.query.middlewares'),
            ])

        ->set(NativeQueryBus::class)
            ->args([
                service('cqs.query_bus'),
            ])

        ->alias(QueryBus::class, NativeQueryBus::class)
    ;
};
