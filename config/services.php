<?php

use Cqs\Command\CommandBus;
use Cqs\Command\NativeCommandBus;
use Cqs\Query\NativeQueryBus;
use Cqs\Query\QueryBus;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
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
        ->set('cqs.null_logger', NullLogger::class)

        ->set('cqs.logger_middleware', LogMessageMiddleware::class)
            ->args([
                service('logger'),
            ])
            ->tag('cqs.command.middleware')
            ->tag('cqs.query.middleware')

        ->alias('logger', LoggerInterface::class)
        ->alias('logger', 'cqs.null_logger')

        ->set('cqs.command.handle_middleware', HandleMessageMiddleware::class)
            ->args([
                abstract_arg('cqs.command.handlers_locator'),
                HandlersCountPolicy::SINGLE_HANDLER,
            ])
            ->tag('cqs.command.middleware')

        ->set('cqs.message_bus.command', NativeMessageBus::class)
            ->args([
                tagged_iterator('cqs.command.middleware'),
            ])

        ->set(NativeCommandBus::class)
            ->args([
                service('cqs.message_bus.command'),
            ])

        ->alias(CommandBus::class, NativeCommandBus::class)

        ->set('cqs.query.handle_middleware', HandleMessageMiddleware::class)
            ->args([
                abstract_arg('cqs.query.handlers_locator'),
                HandlersCountPolicy::SINGLE_HANDLER,
            ])
            ->tag('cqs.query.middleware')

        ->set('cqs.message_bus.query', NativeMessageBus::class)
            ->args([
                tagged_iterator('cqs.query.middleware'),
            ])

        ->set(NativeQueryBus::class)
            ->args([
                service('cqs.message_bus.query'),
            ])

        ->alias(QueryBus::class, NativeQueryBus::class)
    ;
};
