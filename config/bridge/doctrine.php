<?php

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Yceruto\CqsBundle\Bridge\Doctrine\Middleware\DoctrineTransactionMiddleware;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('cqs.command.doctrine_translation_middleware', DoctrineTransactionMiddleware::class)
            ->args([
                service(EntityManagerInterface::class),
            ])
            ->tag('cqs.command.middleware')
    ;
};
