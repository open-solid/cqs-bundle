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

namespace OpenSolid\Tests\CqsBundle\Functional;

class SymfonyBusesTest extends AbstractWebTestCase
{
    public function testInjection(): void
    {
        $client = self::createClient();
        $client->request('GET', '/dummy');

        self::assertResponseIsSuccessful();
        self::assertSame('OK', $client->getInternalResponse()->getContent());
    }
}
