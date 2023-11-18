<?php

use Cqs\Query\Query;
use Cqs\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Messenger\MessageBusInterface;

return static function (ContainerBuilder $container) {
    if (!interface_exists(MessageBusInterface::class)) {
        return;
    }

    $config = [
        'messenger' => [
            'default_bus' => 'command.bus',
            'buses' => [
                'command.bus' => [
                    'middleware' => [
                        'router_context',
                    ],
                ],
                'event.bus' => [
                    'default_middleware' => 'allow_no_handlers',
                    'middleware' => [
                        'router_context',
                    ],
                ],
                'query.bus' => null,
            ],
            'transports' => [
                'sync' => 'sync://',
                'async' => [
                    'dsn' => '%env(MESSENGER_TRANSPORT_DSN)%',
                ],
            ],
            'routing' => [
                Command::class => 'sync',
                Query::class => 'sync',
            ],
        ],
    ];

    if (interface_exists(EntityManagerInterface::class)) {
        $config['messenger']['buses']['command.bus']['middleware'][] = 'doctrine_transaction';
        $config['messenger']['failure_transport'] = 'failed';
        $config['messenger']['transports']['failed'] = 'doctrine://default?queue_name=failed';
    }

    $container->prependExtensionConfig('framework', $config);
};
