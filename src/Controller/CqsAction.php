<?php

declare(strict_types=1);

namespace OpenSolid\CqsBundle\Controller;

use OpenSolid\Cqs\Command\CommandBus;
use OpenSolid\Cqs\Query\QueryBus;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class CqsAction implements ServiceSubscriberInterface
{
    use ServiceMethodsSubscriberTrait;

    #[SubscribedService]
    protected function commandBus(): CommandBus
    {
        return $this->container->get(__METHOD__);
    }

    #[SubscribedService]
    protected function queryBus(): QueryBus
    {
        return $this->container->get(__METHOD__);
    }
}
