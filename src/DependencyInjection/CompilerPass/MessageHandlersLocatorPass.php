<?php

namespace Yceruto\CqsBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MessageHandlersLocatorPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        $this->processHandlers('cqs.command.handler', 'cqs.command.handle_middleware', $container);
        $this->processHandlers('cqs.query.handler', 'cqs.query.handle_middleware', $container);
    }

    private function processHandlers(string $tagName, string $middlewareId, ContainerBuilder $container): void
    {
        $refs = [];
        $handlers = $this->findAndSortTaggedServices(
            new TaggedIteratorArgument($tagName, 'message'),
            $container,
        );
        foreach ($handlers as $message => $reference) {
            $refs[$message][] = $reference;
        }

        $middleware = $container->findDefinition($middlewareId);
        $middleware->replaceArgument(0, new ServiceLocatorArgument($refs));
    }
}
