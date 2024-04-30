<?php

declare(strict_types=1);

namespace OpenSolid\CqsBundle\Controller;

use OpenSolid\Cqs\Command\CommandBus;
use OpenSolid\Cqs\Query\QueryBus;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

class CqsAction implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

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
