<?php

declare(strict_types=1);

namespace OpenSolid\CqsBundle;

use LogicException;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Messenger\MessageBusInterface;
use OpenSolid\CqsBundle\Attribute\AsCommandHandler;
use OpenSolid\CqsBundle\Attribute\AsQueryHandler;
use OpenSolid\CqsBundle\Controller\CommandAction;
use OpenSolid\CqsBundle\Controller\CqsAction;
use OpenSolid\CqsBundle\Controller\QueryAction;
use OpenSolid\Messenger\Bridge\Symfony\DependencyInjection\CompilerPass\MessageHandlersLocatorPass;
use OpenSolid\Messenger\Bridge\Symfony\DependencyInjection\Configurator\MessageHandlerConfigurator;

class CqsBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition.php');
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MessageHandlersLocatorPass('cqs.command.handler', 'cqs.command.middleware.handler'));
        $container->addCompilerPass(new MessageHandlersLocatorPass('cqs.query.handler', 'cqs.query.middleware.handler'));
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if (interface_exists(MessageBusInterface::class)) {
            $container->import('../config/packages/messenger.php');
        }
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // Allow controllers to be loaded as services
        $builder->registerForAutoconfiguration(CommandAction::class)
            ->addTag('controller.service_arguments');
        $builder->registerForAutoconfiguration(QueryAction::class)
            ->addTag('controller.service_arguments');
        $builder->registerForAutoconfiguration(CqsAction::class)
            ->addTag('controller.service_arguments');

        if ($config['bus']['strategy'] === 'native') {
            MessageHandlerConfigurator::configure($builder, AsCommandHandler::class, 'cqs.command.handler');
            MessageHandlerConfigurator::configure($builder, AsQueryHandler::class, 'cqs.query.handler');

            $container->import('../config/native.php');
        } else {
            if (!interface_exists(MessageBusInterface::class)) {
                throw new LogicException('The "symfony" strategy requires symfony/messenger package.');
            }

            MessageHandlerConfigurator::configure($builder, AsCommandHandler::class, 'messenger.message_handler', ['bus' => 'command.bus']);
            MessageHandlerConfigurator::configure($builder, AsQueryHandler::class, 'messenger.message_handler', ['bus' => 'query.bus']);

            $container->import('../config/messenger.php');
        }
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
