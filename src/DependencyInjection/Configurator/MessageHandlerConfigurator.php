<?php

namespace Yceruto\CqsBundle\DependencyInjection\Configurator;

use ReflectionNamedType;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

readonly class MessageHandlerConfigurator
{
    public static function configure(ContainerBuilder $builder, string $attributeClass, string $tagName): void
    {
        $builder->registerAttributeForAutoconfiguration($attributeClass, new self($attributeClass, $tagName));
    }

    public function __invoke(ChildDefinition $definition, object $attribute, \ReflectionClass $reflectionClass): void
    {
        if (!$reflectionClass->hasMethod('__invoke')) {
            return;
        }

        $reflectionMethod = $reflectionClass->getMethod('__invoke');

        if (0 === $reflectionMethod->getNumberOfParameters()) {
            return;
        }

        $type = $reflectionMethod->getParameters()[0]->getType();

        if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
            return;
        }

        if ($attribute instanceof $this->attributeClass) {
            $definition->addTag($this->tagName, ['message' => $type->getName()]);
        }
    }

    private function __construct(
        private string $attributeClass,
        private string $tagName,
    ) {
    }
}
