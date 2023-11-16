<?php

namespace Yceruto\Tests\CqsBundle\Functional;

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
