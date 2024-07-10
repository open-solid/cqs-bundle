<?php

declare(strict_types=1);

namespace OpenSolid\CqsBundle\Controller;

use OpenSolid\Cqs\Command\CommandBus;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class CommandAction implements ServiceSubscriberInterface
{
    use ServiceMethodsSubscriberTrait;

    #[SubscribedService]
    protected function commandBus(): CommandBus
    {
        return $this->container->get(__METHOD__);
    }
}
