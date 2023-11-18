<?php

namespace Yceruto\Tests\CqsBundle\Functional\App\SymfonyBuses\Controller;

use Cqs\Command\SymfonyCommandBus;
use Cqs\Query\SymfonyQueryBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Yceruto\CqsBundle\Controller\CqsAction;

class SymfonyController extends CqsAction
{
    #[Route('/dummy')]
    public function __invoke(): Response
    {
        if ($this->commandBus() instanceof SymfonyCommandBus && $this->queryBus() instanceof SymfonyQueryBus) {
            return new Response('OK');
        }

        return new Response('KO', 500);
    }
}
