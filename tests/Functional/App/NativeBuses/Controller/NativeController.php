<?php

namespace Yceruto\Tests\CqsBundle\Functional\App\NativeBuses\Controller;

use Cqs\Command\NativeCommandBus;
use Cqs\Query\NativeQueryBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Yceruto\CqsBundle\Controller\CqsAction;

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
