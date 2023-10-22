<?php

namespace Yceruto\CqsBundle;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Yceruto\CqsBundle\Attribute\AsCommandHandler;
use Yceruto\CqsBundle\Attribute\AsQueryHandler;

class CqsBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->registerAttributeForAutoconfiguration(AsCommandHandler::class, $this->configureAsMessageHandler(...));
        $builder->registerAttributeForAutoconfiguration(AsQueryHandler::class, $this->configureAsMessageHandler(...));

        $container->import('../config/services.php');
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    protected function configureAsMessageHandler(ChildDefinition $definition, AsCommandHandler|AsQueryHandler $attribute, \ReflectionClass $reflectionClass): void
    {
        if (!$reflectionClass->hasMethod('__invoke')) {
            return;
        }

        $reflectionMethod = $reflectionClass->getMethod('__invoke');

        if (0 === $reflectionMethod->getNumberOfParameters()) {
            return;
        }

        $type = $reflectionMethod->getParameters()[0]->getType();

        if (null === $type || $type->isBuiltin()) {
            return;
        }

        if ($attribute instanceof AsQueryHandler) {
            $definition->addTag('cqs.query_handler', ['query' => $type->getName()]);
        } else {
            $definition->addTag('cqs.command_handler', ['command' => $type->getName()]);
        }
    }
}
