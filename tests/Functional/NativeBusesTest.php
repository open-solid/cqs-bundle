<?php

namespace Yceruto\Tests\CqsBundle\Functional;

class NativeBusesTest extends AbstractWebTestCase
{
    public function testInjection(): void
    {
        $client = self::createClient();
        $client->request('GET', '/dummy');

        self::assertResponseIsSuccessful();
        self::assertSame('OK', $client->getInternalResponse()->getContent());
    }
}
