<?php

namespace Yceruto\CqsBundle\Middleware\Doctrine;

use Cqs\Messenger\Envelop;
use Cqs\Messenger\Middleware\Middleware;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineTransactionMiddleware implements Middleware
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function handle(Envelop $envelop, callable $next): void
    {
        $this->em->wrapInTransaction(static fn () => $next($envelop));
    }
}
