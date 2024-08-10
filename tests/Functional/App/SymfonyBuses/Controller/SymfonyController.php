<?php

declare(strict_types=1);

/*
 * This file is part of OpenSolid package.
 *
 * (c) Yonel Ceruto <open@yceruto.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSolid\Tests\CqsBundle\Functional\App\SymfonyBuses\Controller;

use OpenSolid\Cqs\Command\Bridge\SymfonyCommandBus;
use OpenSolid\Cqs\Query\Bridge\SymfonyQueryBus;
use OpenSolid\CqsBundle\Action\CqsAction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
