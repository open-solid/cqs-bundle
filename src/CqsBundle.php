<?php

namespace Yceruto\CqsBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Yceruto\CqsBundle\Attribute\AsCommandHandler;
use Yceruto\CqsBundle\Attribute\AsQueryHandler;
use Yceruto\CqsBundle\Controller\CommandAction;
use Yceruto\CqsBundle\Controller\CqsAction;
use Yceruto\CqsBundle\Controller\QueryAction;
use Yceruto\Messenger\Bridge\Symfony\DependencyInjection\CompilerPass\MessageHandlersLocatorPass;
use Yceruto\Messenger\Bridge\Symfony\DependencyInjection\Configurator\MessageHandlerConfigurator;

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

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        MessageHandlerConfigurator::configure($builder, AsCommandHandler::class, 'cqs.command.handler');
        MessageHandlerConfigurator::configure($builder, AsQueryHandler::class, 'cqs.query.handler');

        $builder->registerForAutoconfiguration(CommandAction::class)
            ->addTag('controller.service_arguments');
        $builder->registerForAutoconfiguration(QueryAction::class)
            ->addTag('controller.service_arguments');
        $builder->registerForAutoconfiguration(CqsAction::class)
            ->addTag('controller.service_arguments');

        $container->import('../config/services.php');
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
