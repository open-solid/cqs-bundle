<?php

namespace OpenSolid\CqsBundle\Middleware\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use OpenSolid\Messenger\Middleware\Middleware;
use OpenSolid\Messenger\Model\Envelope;

readonly class DoctrineTransactionMiddleware implements Middleware
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function handle(Envelope $envelope, callable $next): void
    {
        $this->em->wrapInTransaction(static fn () => $next($envelope));
    }
}
