<?php

namespace Yceruto\Tests\CqsBundle\Functional\App\NativeBuses\Controller;

use Cqs\Command\CommandBus;
use Cqs\Command\NativeCommandBus;
use Cqs\Query\NativeQueryBus;
use Cqs\Query\QueryBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class NativeController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
    }

    #[Route('/dummy')]
    public function __invoke(): Response
    {
        if ($this->commandBus instanceof NativeCommandBus && $this->queryBus instanceof NativeQueryBus) {
            return new Response('OK');
        }

        return new Response('KO', 500);
    }
}
