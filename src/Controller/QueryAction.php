<?php

declare(strict_types=1);

namespace OpenSolid\CqsBundle\Controller;

use OpenSolid\Cqs\Query\QueryBus;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

class QueryAction implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    #[SubscribedService]
    protected function queryBus(): QueryBus
    {
        return $this->container->get(__METHOD__);
    }
}
