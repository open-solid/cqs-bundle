<?php

use Cqs\Command\CommandBus;
use Cqs\Command\NativeCommandBus;
use Cqs\Query\NativeQueryBus;
use Cqs\Query\QueryBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Yceruto\Messenger\Bus\NativeMessageBus;
use Yceruto\Messenger\Handler\HandlersCountPolicy;
use Yceruto\Messenger\Middleware\HandleMessageMiddleware;
use Yceruto\Messenger\Middleware\LogMessageMiddleware;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('cqs.middleware.logger', LogMessageMiddleware::class)
            ->args([
                service('logger'),
            ])
            ->tag('cqs.command.middleware')
            ->tag('cqs.query.middleware')

        ->set('cqs.command.middleware.handler', HandleMessageMiddleware::class)
            ->args([
                abstract_arg('cqs.command.middleware.handler.locator'),
                HandlersCountPolicy::SINGLE_HANDLER,
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

        ->set('cqs.query.middleware.handler', HandleMessageMiddleware::class)
            ->args([
                abstract_arg('cqs.query.middleware.handler.locator'),
                HandlersCountPolicy::SINGLE_HANDLER,
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
