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

namespace OpenSolid\Tests\CqsBundle\Functional\App\NativeBuses\Controller;

use OpenSolid\Cqs\Command\NativeCommandBus;
use OpenSolid\Cqs\Query\NativeQueryBus;
use OpenSolid\CqsBundle\Action\Action;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NativeController extends Action
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
