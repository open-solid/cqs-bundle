<?php

namespace Yceruto\CqsBundle\DependencyInjection\Configurator;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yceruto\CqsBundle\Attribute\AsCommandHandler;
use Yceruto\CqsBundle\Attribute\AsQueryHandler;

class MessageHandlerConfigurator
{
    public static function configure(ContainerBuilder $builder): void
    {
        $configurator = new self();
        $builder->registerAttributeForAutoconfiguration(AsCommandHandler::class, $configurator);
        $builder->registerAttributeForAutoconfiguration(AsQueryHandler::class, $configurator);
    }

    public function __invoke(ChildDefinition $definition, AsCommandHandler|AsQueryHandler $attribute, \ReflectionClass $reflectionClass): void
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
