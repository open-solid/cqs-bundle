<?php

use OpenSolid\Cqs\Query\Query;
use OpenSolid\Cqs\Command\Command;
use OpenSolid\DomainEvent\Infrastructure\Event\Bus\Middleware\SymfonyEventPublisherMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $container) {
    $config = [
        'messenger' => [
            'default_bus' => 'command.bus',
            'buses' => [
                'command.bus' => [
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

        if (class_exists(SymfonyEventPublisherMiddleware::class)) {
            $config['messenger']['buses']['command.bus']['middleware'][] = 'publish_domain_events';
        }
    }

    $container->prependExtensionConfig('framework', $config);
};
