<?php

namespace Yceruto\CqsBundle\Middleware\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Yceruto\Messenger\Middleware\Middleware;
use Yceruto\Messenger\Model\Envelope;

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
