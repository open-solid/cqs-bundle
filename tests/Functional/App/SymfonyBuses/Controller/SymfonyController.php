<?php

namespace Yceruto\Tests\CqsBundle\Functional\App\SymfonyBuses\Controller;

use Cqs\Command\CommandBus;
use Cqs\Command\SymfonyCommandBus;
use Cqs\Query\QueryBus;
use Cqs\Query\SymfonyQueryBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class SymfonyController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
    }

    #[Route('/dummy')]
    public function __invoke(): Response
    {
        if ($this->commandBus instanceof SymfonyCommandBus && $this->queryBus instanceof SymfonyQueryBus) {
            return new Response('OK');
        }

        return new Response('KO', 500);
    }
}
