<?php

namespace OpenSolid\Tests\CqsBundle\Functional\App\NativeBuses\Controller;

use OpenSolid\Cqs\Command\NativeCommandBus;
use OpenSolid\Cqs\Query\NativeQueryBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenSolid\CqsBundle\Controller\CqsAction;

class NativeController extends CqsAction
{
    #[Route('/dummy')]
    public function __invoke(): Response
    {
        if ($this->commandBus() instanceof NativeCommandBus && $this->queryBus() instanceof NativeQueryBus) {
            return new Response('OK');
        }

        return new Response('KO', 500);
    }
}
